<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'food_id', 'order_number'
    ];
    protected $hidden = [
        'created_time', 'updated_time'
    ];
}
