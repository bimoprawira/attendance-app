<?php

namespace App\Exports;

use App\Models\Gaji;
use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Facades\Excel;

class GajiExport implements FromCollection
{
    protected $employeeId;

    public function __construct($employeeId)
    {
        $this->employeeId = $employeeId;
    }

    public function collection()
    {
        return Gaji::where('employee_id', $this->employeeId)->get();
    }
}
