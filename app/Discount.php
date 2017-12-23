<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $table = 'discount';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'start_discount_time', 'end_discount_time', 'type', 'value',
    ];

    public function GetType()
    {
        if ($this->type == "A") return "總價打折";
        if ($this->type == "B") return "總價折扣XX元";
        if ($this->type == "C") return "特定分類打折";
    }

    public function getEncodedCode()
    {
        return static::encrypt($this->id);
    }

    private static function sha256(string $str)
    {
        return strtoupper(hash("sha256", $str));
    }

    private const OCCUPY_DATA = [2, 5, 14, 11, 7, 9];

    public static function encrypt(int $id): string
    {
        $arr = ['0000', '0000', '0000', '0000'];
        //From 0 to 0xfffffff
        $idHex = str_pad(base_convert($id, 10, 36), 6, '0', STR_PAD_LEFT);
        $i = 0;
        for ($i = 0, $j = count(static::OCCUPY_DATA); $i < $j; $i++) {
            $num = static::OCCUPY_DATA[$i];
            $a = (int)floor($num / 4);
            $b = $num % 4;
            $arr[$a][$b] = $idHex[$i];
        }
        $checksum = static::sha256(implode('-', $arr));
        $start = hexdec(substr($checksum, 11, 7));
        for ($a = 0; $a < 4; $a++) {
            for ($b = 0; $b < 4; $b++) {
                $i = $a* 4 + $b;
                if(in_array($i, static::OCCUPY_DATA)) continue;
                $arr[$a][$b] = $checksum[($start + ($i) * 5) % 64];
            }
        }

        return strtoupper(implode('-', $arr));
    }

    public static function decrypt(string $str): ?int
    {
        $removeUnusedChar = function (string $str): string {
            return preg_replace('/[^0-9A-Z]/', '', $str);
        };
        $str = $removeUnusedChar($str);
        $str = strtoupper($str);
        if (strlen($str) === 16) {
            $idHex = '';
            foreach (static::OCCUPY_DATA as $i)
                $idHex .= $str[$i];

            $id = intval(base_convert($idHex, 36, 10));
            $enc = $removeUnusedChar(static::encrypt($id));

            if ($enc === $str)
                return $id;
        }

        return null;
    }
}
