<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BusinessHour extends Model
{
    protected $fillable = [
      'restaurant_id', 'week_day', 'period', 'start_time', 'end_time'
    ];
}
