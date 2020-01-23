<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    protected $fillable = [
        'name', 'email', 'password', 'api_token',
    ];

    protected $hidden = [
        'password', 'api_token',
    ];
}
