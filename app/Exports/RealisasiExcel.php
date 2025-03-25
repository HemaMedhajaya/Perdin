<?php

namespace App\Exports;

use App\Models\TravelExpense;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class RealisasiExcel implements FromView
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function view(): View
    {
        $id = $this->id;
    
        $dataSebelum = TravelExpense::with('travelRequest')
        ->where('travel_request_id', $id)
        ->get();

        $dataSesudah = DB::table('travel_expenses as te')
        ->leftJoin('travel_requests as tr', 'te.travel_request_id', '=', 'tr.id')
        ->leftJoin('travel_requests as tr_realisasi', 'te.travel_request_id_realisasi', '=', 'tr_realisasi.id')
        ->where(function ($query) use ($id) {
            $query->where('te.travel_request_id', $id)
                ->orWhere('te.travel_request_id_realisasi', $id);
        })
        ->select('te.*', 'tr.status_approve_realisasi')
        ->get();

        $dataGabungan = [];

        $jumlahBaris = max($dataSebelum->count(), $dataSesudah->count());

        for ($i = 0; $i < $jumlahBaris; $i++) {
        $dataGabungan[] = [
            'sebelum' => $dataSebelum[$i] ?? null,
            'sesudah' => $dataSesudah[$i] ?? null,
        ];
        }

        $maxRows = max($dataSebelum->count(), $dataSesudah->count());

        return view('exports.realisasiexcel', [
        'dataGabungan' => $dataGabungan,
        'totalsebelum' => $dataSebelum->sum('total'),
        'totalsesudah' => $dataSesudah->sum(fn($item) => $item->total_realisasi ?? $item->total),
        'maxRows' => $maxRows,  
        ]);

    }
}
