<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Gaji;
use Carbon\Carbon;

class GajiSeeder extends Seeder
{
    public function run(): void
    {
        $month = Carbon::now()->format('Y-m');
        $employees = Employee::all();
        foreach ($employees as $employee) {
            $exists = Gaji::where('employee_id', $employee->employee_id)
                ->where('periode_bayar', $month)
                ->exists();
            if (!$exists) {
                Gaji::create([
                    'employee_id' => $employee->employee_id,
                    'periode_bayar' => $month,
                    'gaji_pokok' => $employee->gaji_pokok,
                    'komponen_tambahan' => 0,
                    'potongan' => 0,
                    'status' => 'pending',
                ]);
            }
        }
    }
} 