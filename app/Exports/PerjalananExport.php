<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\FromCollection;

class PerjalananExport implements FromCollection, WithHeadings, WithStyles, WithCustomStartCell
{
    public function collection()
    {
        return collect([
            [1, 3, 2, 7, 'Rp 6.500.000', 'Rp 10.300.000', 'Andi', '05-01-2024', '10-01-2024', 3, 5, 'Rp 5.700.000', 'Rp 5.700.000', 'Selesai', 'Rp 1.200.000'],
            [2, 2, 4, 6, 'Rp 5.000.000', 'Rp 7.900.000', 'Budi', '07-01-2024', '12-01-2024', 2, 4, 'Rp 4.200.000', 'Rp 4.200.000', 'Selesai', 'Rp 950.000'],
        ]);
    }

    public function headings(): array
    {
        return [
            ['No', 'BUDGET 1', '', '', '', 'TOTAL BUDGET', 'TAHAP 1', '', '', '', '','', 'TOTAL REALISASI', 'KETERANGAN', 'BUDGET - PERDIN'],
            ['', 'DELIVERY', 'MAN', 'DAY', 'AMOUNT', '', 'PIC', 'TGL PERGI', 'TGL PLG', 'MAN', 'DAY', 'TOTAL', '', '', ''],
        ];
    }

    public function startCell(): string
    {
        return 'A1'; // Mulai dari A1
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();

        $sheet->mergeCells('A1:A2'); 
        $sheet->mergeCells('F1:F2'); 
        $sheet->mergeCells('B1:E1');
        $sheet->mergeCells('G1:L1'); 
        $sheet->mergeCells('M1:M2');  
        $sheet->mergeCells('N1:N2'); 
        $sheet->mergeCells('O1:O2'); 

        // **Style Header dengan Warna Sesuai**
        $headerStyles = [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];

        // **Warna Background Header**
        $sheet->getStyle('A1:F2')->applyFromArray(array_merge($headerStyles, [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFD8A8'], // Orange
            ],
        ]));

        $sheet->getStyle('G1:L2')->applyFromArray(array_merge($headerStyles, [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'C3E6CB'], // Hijau
            ],
        ]));

        $sheet->getStyle('M1:N2')->applyFromArray(array_merge($headerStyles, [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'A7C7E7'], // Biru
            ],
        ]));

        $sheet->getStyle('O1:O2')->applyFromArray(array_merge($headerStyles, [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
            ],
        ]));

        // **Style Data**
        $sheet->getStyle('A3:O' . $lastRow)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        // **Auto-size Kolom**
        foreach (range('A', 'O') as $col) { 
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

}
