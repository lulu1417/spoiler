<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = [
      'name',  'image', 'coordinate', 'operating_time', 'address', 'link', 'assessment' ,
    ];
    protected $hidden = [
      'created_time', 'updated_time'
    ];
}
