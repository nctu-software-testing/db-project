<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
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
        'name','start_discount_time','end_discount_time','type','value',
    ];

    public function GetType()
    {
        if($this->type=="A")return "總價打折";
        if($this->type=="B")return "總價折扣XX元";
        if($this->type=="C")return "特定分類打折";
    }
    
    public function getEncodedCode()
    {
        return static::encrypt($this->id);
    }
    
    public static function encrypt(int $id) : string
    {
        $id = strval($id);
        $crc32 = strtoupper(str_pad(dechex(crc32($id)), 8, '0', STR_PAD_LEFT));
        $dataBase64 = base64_encode($id);
        
        return $crc32.$dataBase64;
    }

    public static function decrypt(string $str) : ?int
    {
        $crc32Hex = substr($str, 0, 8);
        $data = substr($str, 8);
        $b64DecodedData = base64_decode($data);
        $crc32 = hexdec($crc32Hex);
        if(crc32($b64DecodedData)===$crc32)
        {
            return intval($b64DecodedData);
        }
        
        return null;
    }
}
