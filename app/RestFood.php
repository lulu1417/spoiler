<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RestFood extends Model
{
    protected $fillable = [
        'restaurant_id', 'food_id'
    ];
    protected $hidden = [
        'created_time', 'updated_time'
    ];
}
