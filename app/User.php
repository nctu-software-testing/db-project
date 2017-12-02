<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public const ADMIN_ROLE = 'A';
    public const BUSINESS_ROLE = 'B';
    public const CUSTOMER_ROLE = 'C';
    protected $table = 'user';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account', 'sn', 'password', 'role', 'name', 'birthday', 'gender', 'email', 'enable', 'avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function getAvatarUrl()
    {
        if (empty($this->avatar)) {
            return asset('images/avatar.svg');
        } else {
            return 'https://i.imgur.com/' . $this->avatar . 'b.jpg';
        }
    }
}
