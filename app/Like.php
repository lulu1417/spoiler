<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = [
        'user_id', 'restaurant_id', 'send'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    function user(){
        return $this->belongsTo(User::class);
    }

    function restaurant(){
        return $this->belongsTo(Restaurant::class);
    }
}
