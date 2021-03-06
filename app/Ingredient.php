<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = [
        'name', 'type'
    ];
    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
