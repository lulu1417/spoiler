<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    protected $fillable = [
        'name', 'account', 'password', 'api_token', 'phone',
    ];

    protected $hidden = [
        'password', 'created_at', 'updated_at', 'account_verified_at'
    ];
}
