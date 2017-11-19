<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use DateTime;

class Order extends Model
{
    protected $table = 'order';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'location_id','customer_id','state','order_time','sent_time','arrival_time','final_cost','discount_id',
    ];

    public function GetState()
    {
        $this->RenewState();
        if($this->state=="0")return "出貨中";
        if($this->state=="1")return "運送中";
        if($this->state=="2")return "已送達";
    }

    private function RenewState()
    {
        $st=$this->sent_time;
        $at=$this->arrival_time;
        $now = date('Y-m-d H:i:s');
            $this->state=0;
        if($now>$st)
            $this->state=1;
        if($now>$at)
            $this->state=2;
        $this->save();

    }
    public function GetId()
    {
        return $this->id;
    }
    public function GetLink()
    {
        return "orderDetail?id=".$this->id;
    }
}
