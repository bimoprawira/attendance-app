<?php

namespace App\Exports;

use App\Models\Gaji;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GajiExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $employee_id;

    public function __construct($employee_id)
    {
        $this->employee_id = $employee_id;
    }

    public function collection()
    {
        return Gaji::where('employee_id', $this->employee_id)
                   ->orderBy('created_at', 'desc')
                   ->get();
    }

    public function headings(): array
    {
        return [
            'Periode',
            'Gaji Pokok',
            'Komponen Tambahan',
            'Potongan',
            'Total Gaji',
        ];
    }

    public function map($gaji): array
    {
        $total = $gaji->gaji_pokok + ($gaji->komponen_tambahan ?? 0) - ($gaji->potongan ?? 0);
        
        return [
            $gaji->periode_bayar,
            'Rp ' . number_format($gaji->gaji_pokok, 0, ',', '.'),
            'Rp ' . number_format($gaji->komponen_tambahan ?? 0, 0, ',', '.'),
            'Rp ' . number_format($gaji->potongan ?? 0, 0, ',', '.'),
            'Rp ' . number_format($total, 0, ',', '.'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style the header row
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'E2E8F0', // Tailwind's gray-200
                ],
            ],
        ]);

        // Auto-size columns
        foreach(range('A', 'E') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
} 