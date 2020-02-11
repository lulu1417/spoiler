<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Owner extends Authenticatable
{
    protected $fillable = [
        'name', 'account', 'password', 'api_token', 'phone',
    ];

    protected $hidden = [
        'password', 'created_at', 'updated_at', 'account_verified_at'
    ];
}
