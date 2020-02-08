<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = [
      'name', 'class', 'image', 'coordinate', 'address', 'link', 'assessment', 'phone', 'owner_id',
    ];
    protected $hidden = [
      'created_at', 'updated_at'
    ];

    public function foods()
    {
        return $this->hasMany('App\Food');
    }

    public function businessHours()
    {
        return $this->hasMany('App\BusinessHour');
    }

    public function owners()
    {
        return $this->belongsTo('App\Owner');
    }

}
