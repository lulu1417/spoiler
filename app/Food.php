<?php

namespace App;

use App\Events\TestEvent;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    protected $fillable = [
     'name', 'remaining', 'original_price', 'discounted_price', 'image', 'restaurant_id'
    ];
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'id');
    }
    protected $dispatchesEvents = [
        'saving' => TestEvent::class,
    ];

}
