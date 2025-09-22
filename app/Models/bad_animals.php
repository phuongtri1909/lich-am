<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class bad_animals extends Model
{
    protected $fillable = [
        'year_id',
        'month_id',
        'day',
    ];
}
