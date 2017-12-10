<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    protected $table = 'shipping';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lower_bound', 'upper_bound', 'price'
    ];

    public static function getShippingPrice($curPrice): int
    {
        $res = static::where('lower_bound', '<=', $curPrice)
            ->where('upper_bound', '>=', $curPrice)
            ->select('price')
            ->first();

        if ($res) {
            return $res->price;
        }

        return 0;
    }
}
