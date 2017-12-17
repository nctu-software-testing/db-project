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
        'name','start_discount_time','end_discount_time','type','discount_percent',
    ];

    public function GetType()
    {
        if($this->type=="A")return "免運費";
        if($this->type=="B")return "商品類別優惠";
        if($this->type=="C")return "";
    }
}
