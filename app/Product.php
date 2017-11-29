<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'on_product';
    public $timestamps = false;
    public const STATE_DRAFT = 0;
    public const STATE_RELEASE = 1;
    public const STATE_DELETED = 2;

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
}
