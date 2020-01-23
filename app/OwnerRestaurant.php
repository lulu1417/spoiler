<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OwnerRestaurant extends Model
{
    protected $fillable = [
        'owner_id', 'restaurant_id',
    ];
    protected $hidden = [
        'created_time', 'updated_time'
    ];
}
