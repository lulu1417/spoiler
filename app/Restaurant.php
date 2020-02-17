<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = [
      'name', 'class', 'image', 'east_longitude','north_latitude', 'address', 'link', 'assessment', 'phone', 'owner_id', 'start_time', 'end_time'
    ];
    protected $hidden = [
      'created_at', 'updated_at'
    ];

    public function foods()
    {
        return $this->hasMany(Food::class);
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

}
