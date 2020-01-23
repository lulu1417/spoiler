<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    protected $fillable = [
     'name', 'remaining', 'original_price', 'discounted_price', 'image'
    ];
    protected $hidden = [
        'created_time', 'updated_time'
    ];
}
