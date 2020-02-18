<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'food_id', 'order_number', 'complete', 'send'
    ];
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function food()
    {
        return $this->hasOne(Food::class, 'food_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'user_id', 'id');
    }
}
