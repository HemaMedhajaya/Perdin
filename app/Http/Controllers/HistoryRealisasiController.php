<?php

namespace App\Http\Controllers;

use App\Models\TravelExpense;
use App\Models\TravelRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class HistoryRealisasiController extends Controller
{
    protected $name;
    public function __construct()
    {
        $email = session('email');
        if ($email) {
            $this->name = User::where('email', $email)->pluck('name')->first();
        }
    }
    public function index()
    {
        return view('user.historyrealisasi.index', ['name' => $this->name]);
    }

    public function getData(Request $request)
    {
        $userid = session('user_id');
        if ($request->ajax()) {
            $data = TravelRequest::with(['user', 'participants', 'expenses'])
                ->select('id', 'name_project', 'status_approve', 'keperluan', 'lokasikerja') // Pastikan ambil status_approve
                ->where('user_id', $userid)
                ->where('status_approve', 1)
                ->orderBy('id', 'desc');

            return DataTables::of($data)
                ->addColumn('status_and_action', function ($data) {
                    $status = [
                        0 => '<span class="badge bg-label-secondary">Draft</span>',
                        5 => '<span class="badge bg-label-warning">Diproses</span>',
                        1 => '<span class="badge bg-label-success">Disetujui</span>',
                        2 => '<span class="badge bg-label-danger">Ditolak</span>',
                    ];
                $statusHtml = $status[$data->status_approve] ?? '<span class="badge badge-secondary">Tidak Diketahui</span>';
                    return  $statusHtml;
                })
                ->addColumn('action', function ($data) {
                    $realisasiUrl = route('historyrealisasi.realisasi', ['id' => $data->id]);
                
                    return '
                        <a href="' . $realisasiUrl . '" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Realisasi">
                            <i class="bx bx-calendar-check"></i>
                        </a>
                    ';
                })
                ->rawColumns(['status_and_action', 'action']) 
                ->make(true);
        } else {
            return response()->json(['gagal' => 'Data tidak ditemukan!']);
        }
    }

    public function sudahRealisasi()
    {
        return view('user.historyrealisasi.realisasi', ['name' => $this->name]);
    }

    public function getDataCombined($id)
    {
        $dataSesudah = DB::table('travel_expenses as te')
            ->leftJoin('travel_requests as tr', 'te.travel_request_id', '=', 'tr.id')
            ->leftJoin('travel_requests as tr_realisasi', 'te.travel_request_id_realisasi', '=', 'tr_realisasi.id')
            ->where(function($query) use ($id) {
                $query->where('te.travel_request_id', $id)
                    ->orWhere('te.travel_request_id_realisasi', $id);
            })
            ->select('te.*', 'tr.status_approve as status_approve', 'tr_realisasi.status_approve as status_approve_realisasi')
            ->get();

        $totalSesudah = $dataSesudah->sum(function($item) {
            return $item->total_realisasi ?? $item->total;
        });

        $data = [];
        foreach ($dataSesudah as $index => $after) {
            $data[] = [
                'jenis_perjalanan_sesudah' => $after->jenis_perjalanan_realisasi == 1 ? 'Transportasi' : 'Akomodasi',
                'description_sesudah' => $after->description_realisasi ?? $after->description,
                'total_sesudah' => $after->total_realisasi ?? $after->total,
                'action' => '<button class="btn btn-sm btn-info detail-btn" data-id="'.$after->id.' data-toggle="tooltip" data-placement="top" title="Detail""><i class="bx bx-detail"></i></button>'
            ];
        }

        return DataTables::of($data)
            ->with([
                'totalSesudah' => $totalSesudah
            ])
            ->rawColumns(['action'])
            ->make(true);
    }

}
