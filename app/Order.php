<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'order';
    public $timestamps = false;
    public const STATE_SHIPPING = 0;
    public const STATE_TRANSPORTING = 1;
    public const STATE_ARRIVED = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'location_id', 'customer_id', 'state', 'order_time', 'sent_time', 'arrival_time', 'final_cost', 'discount_id' , 'original_cost','shipping_cost',
    ];

    public function GetState()
    {
        $this->RenewState();
        if ($this->state === self::STATE_SHIPPING) return "出貨中";
        if ($this->state === self::STATE_TRANSPORTING) return "運送中";
        if ($this->state === self::STATE_ARRIVED) return "已送達";

        return "";
    }

    private function RenewState()
    {
        $st = $this->sent_time;
        $at = $this->arrival_time;
        $now = date('Y-m-d H:i:s');
        $this->state = 0;
        if ($now > $st)
            $this->state = 1;
        if ($now > $at)
            $this->state = 2;
        $this->save();
    }

    public function location(){
        return $this->belongsTo('App\Location');
    }

    public function getShippingCost(): int
    {
        return Shipping::getShippingPrice($this->final_cost);
    }

    public function discount()
    {
        return $this->belongsTo('App\Discount');
    }

    public function discountAmount():?int
    {
        return $this->original_cost-$this->final_cost;

    }
}
