<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'on_product';
    public $timestamps = false;
    public const STATE_DRAFT = 0;
    public const STATE_RELEASE = 1;
    public const STATE_DELETED = 2;
    private const HOT_MAX_COUNT = 8;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_name', 'product_information', 'start_date', 'end_date', 'price', 'state', 'category_id', 'user_id',
    ];

    public function GetState()
    {
        if ($this->state === self::STATE_DRAFT) return "草稿";
        if ($this->state === self::STATE_RELEASE) return "發佈";
        if ($this->state === self::STATE_DELETED) return "已刪除";

        return "";
    }

    public function GetLink()
    {
        return "item?id=" . $this->id;
    }

    public function GetDateTime($a)
    {
        if (!$this->start_date)
            return "";
        if ($a == 0)
            return explode(" ", $this->start_date)[0];
        if ($a == 1)
            return explode(" ", $this->start_date)[1];
        if ($a == 2)
            return explode(" ", $this->end_date)[0];
        if ($a == 3)
            return explode(" ", $this->end_date)[1];
    }

    public function getUserName()
    {
        return User::Where('id', $this->user_id)->first()->name;
    }

    public function pictures()
    {
        return $this->hasMany('\App\ProductPicture');
    }

    public static function getOnProductsBuilder(Builder $builder = null): Builder
    {
        $now = new DateTime();
        if (is_null($builder)) {
            $builder = Product::whereRaw('1=1');
        }
        $builder
            ->where('state', self::STATE_RELEASE)
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now);

        return $builder;
    }

    public static function getHotProducts(Builder $builder = null): \Illuminate\Database\Eloquent\Collection
    {
        return
            self::getOnProductsBuilder($builder)
                ->limit(self::HOT_MAX_COUNT)
                ->get();
    }
}
