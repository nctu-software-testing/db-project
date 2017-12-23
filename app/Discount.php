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

    private static function crc32(string $str)
    {
        return strtoupper(hash("crc32b", $str));
    }

    public static function encrypt(int $id): string
    {
        $id = strval($id);
        $crc32 = static::crc32($id);
        $dataBase64 = base64_encode($id);

        return $crc32 . $dataBase64;
    }

    public static function decrypt(string $str): ?int
    {
        $crc32Hex = substr($str, 0, 8);
        $data = substr($str, 8);
        $b64DecodedData = base64_decode($data);
        if (static::crc32($b64DecodedData) === $crc32Hex) {
            return intval($b64DecodedData);
        }

        return null;
    }
}
