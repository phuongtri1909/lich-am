<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LunarMonth extends Model
{
    protected $fillable = [
        'year_id',
        'month',
        'description',
    ];
}
