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
        'product_name','product_information','start_date','end_date','price','state','category_id','user_id',
    ];
    public function GetState()
    {
            if($this->state=="0")return "草稿";
            if($this->state=="1")return "發佈";
            if($this->state=="2")return "已刪除";
    }
    public function GetLink()
    {
        return "item?id=".$this->id;
    }
    public function GetDateTime($a)
    {
        if(!$this->start_date)
            return"";
        if($a==0)
        return explode(" ", $this->start_date)[0];
        if($a==1)
            return explode(" ", $this->start_date)[1];
        if($a==2)
            return explode(" ", $this->end_date)[0];
        if($a==3)
            return explode(" ", $this->end_date)[1];
    }

    public function getUserName()
    {
        return User::Where('id',$this->user_id)->first()->name;
    }
}
