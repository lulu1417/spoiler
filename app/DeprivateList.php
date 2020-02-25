<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeprivateList extends Model
{
    protected $fillable = [
      'user_id', 'is_free'
    ];
}
