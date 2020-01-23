<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $fillable = [
        'name', 'restaurant_id'
    ];
    protected $hidden = [
        'created_time', 'updated_time'
    ];
}
