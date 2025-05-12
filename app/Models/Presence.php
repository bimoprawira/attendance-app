<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    use HasFactory;

    protected $primaryKey = 'presence_id';

    protected $fillable = [
        'employee_id',
        'date',
        'check_in',
        'check_out',
        'status'
    ];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime',
        'check_out' => 'datetime'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    /**
     * Ensure all employees have a 'libur' presence for the given date if it's a holiday or weekend.
     */
    public static function ensureLiburPresences($date)
    {
        $isHoliday = false;
        if (class_exists('App\\Models\\Holiday')) {
            $holiday = \App\Models\Holiday::getHoliday($date);
            $isHoliday = $date->isWeekend() || $holiday;
        } else {
            $isHoliday = $date->isWeekend();
        }
        if ($isHoliday) {
            $employees = \App\Models\Employee::all();
            foreach ($employees as $employee) {
                self::updateOrCreate(
                    [
                        'employee_id' => $employee->employee_id,
                        'date' => $date,
                    ],
                    [
                        'status' => 'libur',
                        'check_in' => null,
                        'check_out' => null
                    ]
                );
            }
        }
    }
}
