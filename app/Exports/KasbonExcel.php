<?php

namespace App\Exports;

use App\Models\TravelRequest;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class KasbonExcel implements FromView
{
    protected $id;
    public function __construct($id)
    {
        $this->id = $id;
    }
    public function view(): View
    {
        $data1= TravelRequest::with('participants.user.karyawan.jabatan','penanggungjawab.user','expenses','categorypf','user','karyawan.jabatan','karyawan.departement','karyawan.user')->where('id',$this->id)->first();
        $name1 = $data1->name_project;
        $participants = $data1->participants;
        $total = $data1->expenses->sum('total');
        
        $data = [
            'title' => 'FORM PENGAJUAN KASBON PERJALANAN DINAS',
            'no' => '240090/SK/FAD-HMJ/N/2024',
            'name' => $data1->karyawan->user->name,
            'nik' => $data1->karyawan->nik,
            'department' => $data1->karyawan->departement->name ?? '-',
            'jabatan' => $data1->karyawan->jabatan->name ?? '-',
            'no_telepon' => $data1->karyawan->nomortlp ?? '-',
            'nama_project' => $name1 ?? '-',
            'no_so' => $data1->nomorso ?? '-',
            'lokasi_kerja' => $data1->lokasikerja,
            'keperluan' => $data1->keperluan,
            'category_product' => 'Furniture',
            'peserta_perjalanan' => $participants,
            'penanggung_jawab' => $data1->penanggungjawab->user->name,
            'estimasi_biaya' => $data1->expenses,
            'total_cash_advance' => $total,
            'terbilang' => 'EMPAT BELAS JUTA RUPIAH',
            'pembon' => [
                'pemohon' => $data1->karyawan->user->name,
                'atasan_langsung' => 'HL',
                'tanggal' => '16/10/2024',
                'disetujui_cso' => 'HL',
                'disetujui_direksi' => '',
            ],
        ];

        return view('exports.kasbonexcel', $data);
    }
}
