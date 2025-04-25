<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gaji extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_gaji';

    protected $fillable = [
        'employee_id',
        'gaji_pokok',
        'potongan',
        'komponen_tambahan',
        'periode_bayar',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
}

