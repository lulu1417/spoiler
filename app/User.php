<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'account', 'email', 'password', 'point', 'api_token', 'phone', 'image', 'bad_record', 'access_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'created_at', 'updated_at', 'account_verified_at', 'access_token', 'bad_record'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function subscriptRestaurant()
    {
        return $this->belongsToMany(Restaurant::class, 'subscriptions', 'user_id' ,'restaurant_id');
    }
    public function orderFood()
    {
        return $this->belongsToMany(Food::class, 'orders', 'user_id', 'food_id');
    }
}
