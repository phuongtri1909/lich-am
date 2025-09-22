<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'day',
        'month',
        'is_lunar',
    ];

    const IS_LUNAR_YES = 1;
    const IS_LUNAR_NO = 0;

    public function getTypeAttribute(): string
    {
        return $this->is_lunar === self::IS_LUNAR_YES ? 'Âm lịch' : 'Dương lịch';
    }
}
