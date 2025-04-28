<?php

namespace App\Exports;

use App\Models\Gaji;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AdminGajiExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Gaji::with('employee')
                   ->orderBy('created_at', 'desc')
                   ->get();
    }

    public function headings(): array
    {
        return [
            'Nama Karyawan',
            'Email',
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
            $gaji->employee->name,
            $gaji->employee->email,
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
        $sheet->getStyle('A1:G1')->applyFromArray([
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
        foreach(range('A', 'G') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
} 