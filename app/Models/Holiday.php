<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'name',
        'description'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public static function isHoliday($date)
    {
        return static::whereDate('date', $date)->exists();
    }

    public static function getHoliday($date)
    {
        return static::whereDate('date', $date)->first();
    }
}
