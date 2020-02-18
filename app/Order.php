<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'food_id', 'order_number', 'food_number', 'complete', 'send'
    ];
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function food()
    {
        return $this->belongsTo(Food::class, 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }
}
