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
    protected $jumlahPeserta;

    public function __construct($data)
    {
        $this->data = $data;
        $this->jumlahPeserta = count($data['peserta_perjalanan']); 
    }

    public function collection()
    {
        // Data Peserta Perjalanan Dinas
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
                $no == 1 ? ($this->data['penanggung_jawab'] ?? '-') : '' // Penanggung jawab hanya di baris pertama
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

        // Menghitung total baris untuk peserta dan estimasi biaya
        $totalPesertaRows = count($pesertaData);
        $totalBiayaRows = count($biayaData);

        return collect(array_merge([
            ['', '', '', '', '', '', '', ''],
            [''],
            ['', 'PT HEMA MEDHAJAYA', $this->data['title'], '', ''],
            ['Nama', $this->data['name'], 'Tanggal Pengajuan', now()->format('d F Y')],
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
            ['Pemohon', 'Atasan Langsung:', '', 'Disetujui Oleh:'],
            ['Frontment', 'Manager', '', 'CSO', 'Direksi'],
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
        // Logo Perusahaan
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Company Logo');
        $drawing->setPath(public_path('dist/img/logo_stramm.jpg'));
        $drawing->setCoordinates('A2');
        $drawing->setHeight(50);
        $drawing->setWidth(100);
        $drawing->setOffsetX(10);
        $drawing->setOffsetY(10);
        $drawing->setWorksheet($sheet);
    
        // Merge Cell untuk Logo dan Header
        $sheet->mergeCells('A2:A3');
        $sheet->mergeCells('B2:B3');
        $sheet->mergeCells('C2:E3');
        $sheet->mergeCells('A12:E12');
        $sheet->mergeCells('A13:E13');
    
        // Styling Header
        $sheet->setCellValue('B2', 'PT HEMA MEDHAJAYA');
        $sheet->setCellValue('C2', 'FORM PENGAJUAN KASBON PERJALANAN DINAS LUAR KOTA');
    
        // Header untuk Peserta (A sampai E)
        $sheet->getStyle('A13:E13')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'e0e0e0']],
        ]);
    
        $sheet->getStyle('B2:B3')->applyFromArray([
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
            ],
            'font' => ['bold' => true],
        ]);
    
        $sheet->getStyle('C2:E3')->applyFromArray([
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'font' => ['bold' => true],
        ]);
    
        // Header untuk Peserta (A sampai E)
        $sheet->getStyle('A14:E14')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'f2f2f2']],
        ]);
    
        // Mulai dari baris 12 untuk peserta
        $startPesertaRow = 13;
        $startEstimasiBiayaRow = $startPesertaRow + $this->jumlahPeserta + 1;
        $startEstimasiBiayaRow1 = $startPesertaRow + $this->jumlahPeserta + 2;
        $startEstimasiBiayaRow3 = $startPesertaRow + $this->jumlahPeserta + 3;
        $sheet->mergeCells("A{$startEstimasiBiayaRow1}:E{$startEstimasiBiayaRow1}"); 
        $sheet->mergeCells("A{$startEstimasiBiayaRow3}:E{$startEstimasiBiayaRow3}"); 
        
        // Menyusun header peserta dan estimasi biaya
        $pesertaHeaderRow = $startPesertaRow + $this->jumlahPeserta;
        $biayaHeaderRow = $startEstimasiBiayaRow + count($this->data['estimasi_biaya']) - 1;
    
        // Header untuk Estimasi Biaya
        $sheet->getStyle('A' . $biayaHeaderRow)->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'f2f2f2']],
        ]);
    
        // Menambahkan border ke seluruh konten
        $lastRow = $startEstimasiBiayaRow + count($this->data['estimasi_biaya']);
        $sheet->getStyle("A2:E$lastRow")->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
        ]);
    
        // Auto Size Kolom
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
    

}
