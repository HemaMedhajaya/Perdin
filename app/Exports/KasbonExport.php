<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\FromCollection;

class KasbonExport implements FromCollection, WithStyles, WithCustomStartCell
{
    protected $data;
    public function __construct($data)
    {
        $this->data = $data;
    }
    public function collection()
    {
        $pesertaData = [
            ['Peserta Perjalanan Dinas'],
            ['No', 'Nama', 'Jabatan', 'Penanggung Jawab'],
        ];
    
        $no = 1;
        foreach ($this->data['peserta_perjalanan'] as $peserta) {
            $pesertaData[] = [
                $no,
                $peserta->user->name ?? '-',
                $peserta->user->karyawan->jabatan->name ?? '-',
                $no == 1 ? ($this->data['penanggung_jawab'] ?? '-') : '' // Hanya diisi di baris pertama
            ];
            $no++;
        }

        // Data Estimasi Biaya Perjalanan Dinas
        $biayaData = [
            ['Estimasi Biaya Perjalanan Dinas'],
            ['Deskripsi', 'Biaya', 'Qty', 'Total', 'Keterangan'],
        ];

        foreach ($this->data['estimasi_biaya'] as $biaya) {
            $biayaData[] = [
                $biaya->transportation ?? '-',
                'Rp ' . number_format($biaya->cost, 0, ',', '.') . ' x ' . ($biaya->man ?? 1),
                $biaya->quantity ?? '1',
                'Rp ' . number_format($biaya->total, 0, ',', '.'),
                $biaya->description ?? '-'
            ];
        }

        // Tambahkan total biaya
        $biayaData[] = ['TOTAL CASH ADVANCE', '', '', '', 'Rp ' . number_format($this->data['total_cash_advance'], 0, ',', '.')];

        return collect(array_merge([
            ['', '', '', '', '', '', '', ''],
            [''],
            ['', 'PT HEMA MEDHAJAYA', $this->data['title'], '', ''],
            ['Nama', $this->data['name'], 'Tanggal Pengajuan', '15 October 2024'],
            ['NIK', $this->data['nik'], 'Department', $this->data['department']],
            ['Jabatan', $this->data['jabatan'], 'No Telepon', $this->data['no_telepon']],
            ['Nama Project', $this->data['nama_project'], '', ''],
            ['No SO', $this->data['no_so'], '', ''],
            ['Lokasi Kerja', $this->data['lokasi_kerja'], '', ''],
            ['Keperluan', $this->data['keperluan'], '', ''],
            ['Category Product', $this->data['category_product'], '', ''],
            [''],
        ], $pesertaData, [
            [''],
        ], $biayaData, [            
            ['Pemohon', 'Atasan Langsung:', '','Disetujui Oleh:'],
            ['Frontment', 'Manager', '','CSO', 'Direksi'],
            ['', '', '', ''],
            ['', '', '', ''],
            ['HL', 'HL', ''],
        ]));
    }

    public function startCell(): string
    {
        return 'A1';
    }

    public function styles(Worksheet $sheet)
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Company Logo');
        $drawing->setPath(public_path('dist/img/logo_stramm.jpg')); // Sesuaikan path logo
        $drawing->setCoordinates('A2'); 
        $drawing->setHeight(50);
        $drawing->setWidth(100);
        $drawing->setOffsetX(10);
        $drawing->setOffsetY(10);
        $drawing->setWorksheet($sheet);

        // Merge cell untuk logo agar tetap di tengah
        $sheet->mergeCells('A2:A3');
        $sheet->getStyle('A2:A3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        // Set teks di B2:B3
        $sheet->setCellValue('B2', 'PT HEMA MEDHAJAYA');
        $sheet->mergeCells('B2:B3'); // Merge cell agar teks lebih luas

        // Atur posisi teks agar vertikal tengah & horizontal kanan
        $style = $sheet->getStyle('B2:B3');
        $style->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $style->getFont()->setBold(true);
        
        $sheet->setCellValue('C2', 'FORM PENGAJUAN KASBON PERJALANAN DINAS LUAR KOTA');
        $sheet->mergeCells('C2:E3');

        
        $sheet->mergeCells('A13:E13');
        $sheet->mergeCells('D14:E14');
        $sheet->mergeCells('A17:E17');
        $sheet->mergeCells('A23:D23');
        $sheet->mergeCells('A24:A25');
        $sheet->mergeCells('D24:E24');
        $sheet->mergeCells('A26:A27');
        $sheet->mergeCells('B26:B27');
        $sheet->mergeCells('C26:C27');
        $sheet->mergeCells('D26:D27');
        $sheet->mergeCells('E26:E27');
        $sheet->mergeCells('D4:E4');
        $sheet->mergeCells('D5:E5');
        $sheet->mergeCells('D6:E6');
        $sheet->mergeCells('B7:E7');
        $sheet->mergeCells('B8:E8');
        $sheet->mergeCells('B9:E9');
        $sheet->mergeCells('B10:E10');
        $sheet->mergeCells('B11:E11');
        $sheet->mergeCells('A12:E12');
        $sheet->mergeCells('D15:E15');
        $sheet->mergeCells('A16:E16');

        $sheet->getStyle('A24:A25')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A24:A25')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Perbaikan untuk sel yang tidak di-merge
        $sheet->getStyle('A28')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B24')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B25')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B28')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D25')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E25')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('B8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        
        // Jika perlu alignment vertikal
        $sheet->getStyle('A28')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('B24')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('B25')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('B28')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('D25')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('E25')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        
        // Pastikan merge cell juga tetap center
        $sheet->getStyle('D24:E24')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        

        // Atur teks FORM PENGAJUAN KASBON agar center
        $formStyle = $sheet->getStyle('C2:E3');
        $formStyle->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $formStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $formStyle->getFont()->setBold(true);


        
        // Styling judul
        $sheet->getStyle('A2:C2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getStyle('D2:F2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Border tabel
        $lastRow = 28;
        $sheet->getStyle("A2:E$lastRow")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        // Warna header tabel
        $sheet->getStyle('A4:A11')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F2F3F3']]
        ]);
        $sheet->getStyle('C4:C6')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F2F3F3']]
        ]);
        $sheet->getStyle('A13:E13')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'e0e0e0']]
        ]);
        $sheet->getStyle('A14:E14')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F2F3F3']]
        ]);
        $sheet->getStyle('A17:E17')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'e0e0e0']]
        ]);
        $sheet->getStyle('A18:E18')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F2F3F3']]
        ]);
        $sheet->getStyle('A23:D23')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F2F3F3']]
        ]);

        // Format angka
        $sheet->getStyle('D18:D22')->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('E23')->getNumberFormat()->setFormatCode('"Rp " #,##0');

        // Auto size kolom
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
}
