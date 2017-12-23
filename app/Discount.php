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
}
