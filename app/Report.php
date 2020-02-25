<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'content', 'object_id', 'reporter_is_user', 'reporter_id'
    ];
    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
