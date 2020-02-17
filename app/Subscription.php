<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'restaurant_id', 'user_id',
    ];
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function restaurant()
    {
        return $this->hasMany(Restaurant::class);
    }
}
