<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AllergyFood extends Model
{
    protected $fillable = [
        'food_id', 'ingredient_id'
    ];
    protected $hidden = [
        'created_time', 'updated_time'
    ];
}
