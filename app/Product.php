<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'on_product';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_name','product_information','expiration_date','end_date','price','state','category_id','user_id',
    ];
    public function GetState()
    {
            if($this->state=="0")return "草稿";
            if($this->state=="1")return "發佈";
            if($this->state=="2")return "移除";
    }
    public function GetLink()
    {
        return "item?id=".$this->id;
    }
}
