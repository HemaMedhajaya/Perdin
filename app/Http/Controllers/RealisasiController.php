<?php

namespace App\Http\Controllers;

use App\Exports\PerjalananExport;
use App\Exports\RealisasiExcel;
use App\Models\TravelExpense;
use App\Models\TravelRealisasi;
use App\Models\TravelRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class RealisasiController extends Controller
{
    protected $name;

    public function __construct()
    {
        $email = session('email');
        if ($email) {
            $this->name = User::where('email', $email)->pluck('name')->first();
        }
    }

    public function index($id)
    {
        return view('user.travel.realisasi', ['name' => $this->name, 'id' => $id]);
    }

    public function getDataRealisasi($id)
    {
        $data = TravelRealisasi::where('travel_request_id', $id)->get();
        $totalKeseluruhan = $data->sum('total');

        return DataTables::of($data)
            ->addColumn('jenis_perjalanan', function ($data) {
                return $data->jenis_perjalanan == 1 ? 'Transportasi' : 'Akomodasi';
            })
            ->addColumn('description', function ($data) {
                return $data->description ?: '-';
            })
            ->addColumn('total', function ($data) {
                return $data->total ?: '-';
            })
            ->addColumn('action', function ($data) {
                return '
                    <button class="btn btn-sm btn-primary edit-btn" data-id="' . $data->id . '" data-toggle="tooltip" data-placement="top" title="Edit">
                        <i class="bx bx-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="' . $data->id . '" data-toggle="tooltip" data-placement="top" title="Hapus">
                        <i class="bx bx-trash"></i>
                    </button>
                ';
            })
            ->with('totalKeseluruhan', $totalKeseluruhan)
            ->rawColumns(['action']) 
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'travel_request_id' => 'required|exists:travel_requests,id',
            'jenis_biaya' => 'required|string',
            'deskripsi' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'biaya' => 'required|numeric',
            'qty' => 'required|integer|min:1',
            'total' => 'required|numeric',
        ]);

        TravelExpense::create([
            'travel_request_id_realisasi' => $request->travel_request_id,
            'jenis_perjalanan_realisasi' => $request->jenis_biaya,
            'description_realisasi' => $request->keterangan,
            'cost_realisasi' => $request->biaya,
            'quantity_realisasi' => $request->qty,
            'total_realisasi' => $request->total,
            'transportation_realisasi' => $request->deskripsi
        ]);

        return response()->json([
            'berhasil' => 'Data berhasil disimpan!',
        ]);
    }

    public function edit($id)
    {
        $data = TravelExpense::find($id);
        
        if (!$data) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        $response = [
            'id' => $data->id,
            'travel_request_id' => $data->travel_request_id_realisasi ?? $data->travel_request_id,
            'jenis_perjalanan' => $data->jenis_perjalanan_realisasi ?? $data->jenis_perjalanan,
            'transportation' => $data->transportation_realisasi ?? $data->transportation,
            'description' => $data->description_realisasi ?? $data->description,
            'cost' => $data->cost_realisasi ?? $data->cost,
            'quantity' => $data->quantity_realisasi ?? $data->quantity,
            'total' => $data->total_realisasi ?? $data->total,
        ];

        return response()->json($response);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'travel_request_id' => 'required|exists:travel_requests,id',
            'jenis_biaya' => 'required|string',
            'deskripsi' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'biaya' => 'required|numeric',
            'qty' => 'required|integer|min:1',
            'total' => 'required|numeric',
        ]);
        $travelExpense = TravelExpense::find($id);

        if ($travelExpense) {
            $travelExpense->update([
                'jenis_perjalanan_realisasi' => $request->jenis_biaya,
                'description_realisasi' => $request->keterangan,
                'cost_realisasi' => $request->biaya,
                'quantity_realisasi' => $request->qty,
                'total_realisasi' => $request->total,
                'transportation_realisasi' => $request->deskripsi
            ]);
        }
        return response()->json(['berhasil' => 'Biaya perjalanan dinas berhasil diperbarui!']);
    }

    public function destroy($id)
    {
        $travelrealisasi = TravelExpense::find($id);
        $travelrealisasi->delete();
        return response()->json(['berhasil' => 'Realisasi berhaisl dihapus!']);
    }

    public function getDataCombined($id)
    {
        // Data Sebelum
        $dataSebelum = TravelExpense::with('travelRequest')
            ->where('travel_request_id', $id)
            ->get();
        $travelrequest = TravelRequest::find($id);
        $status_approve_realisasi = $travelrequest->status_approve_realisasi;
        // Data Sesudah
        $dataSesudah = DB::table('travel_expenses as te')
            ->leftJoin('travel_requests as tr', 'te.travel_request_id', '=', 'tr.id')
            ->leftJoin('travel_requests as tr_realisasi', 'te.travel_request_id_realisasi', '=', 'tr_realisasi.id')
            ->where(function($query) use ($id) {
                $query->where('te.travel_request_id', $id)
                    ->orWhere('te.travel_request_id_realisasi', $id);
            })
            ->select('te.*', 'tr.status_approve_realisasi as status_approve_realisasi')
            ->get();

        // Hitung Total Keseluruhan Sebelum Realisasi
        $totalSebelum = $dataSebelum->sum('total');

        // Hitung Total Keseluruhan Setelah Realisasi
        $totalSesudah = $dataSesudah->sum(function($item) {
            return $item->total_realisasi ?? $item->total;
        });

        // Format data agar bisa ditampilkan dalam satu tabel
        $data = [];
        $maxRows = max($dataSebelum->count(), $dataSesudah->count());

        for ($i = 0; $i < $maxRows; $i++) {
            $before = $dataSebelum[$i] ?? null;
            $after = $dataSesudah[$i] ?? null;

            $data[] = [
                'jenis_perjalanan_sebelum' => $before ? ($before->jenis_perjalanan == 1 ? 'Transportasi' : 'Akomodasi') : '',
                'description_sebelum' => $before ? $before->description : '',
                'total_sebelum' => $before ? $before->total : '',

                'no_sesudah' => $after ? $i + 1 : '',
                'jenis_perjalanan_sesudah' => $after ? ($after->jenis_perjalanan_realisasi == 1 ? 'Transportasi' : 'Akomodasi') : '',
                'description_sesudah' => $after ? ($after->description_realisasi ?? $after->description) : '',
                'total_sesudah' => $after ? ($after->total_realisasi ?? $after->total) : '',
                'status_approve_realisasi' => $after ? (
                                [
                                    2 => '<span class="badge bg-label-warning">Diproses</span>',
                                    1 => '<span class="badge bg-label-secondary">Draft</span>',
                                    3 => '<span class="badge bg-label-danger">Ditolak</span>',
                                    4 => '<span class="badge bg-label-success">Disetujui</span>',
                                ][$after->status_approve_realisasi] ?? ''
                            ) : '',
                'action' => $after ? '<button class="btn btn-sm btn-primary edit-btn" data-id="'.$after->id.'"><i class="bx bx-edit"></i></button> 
                                    <button class="btn btn-sm btn-danger delete-btn" data-id="'.$after->id.'"><i class="bx bx-trash"></i></button>' : ''
            ];
        }

        return DataTables::of($data)
            ->with([
                'totalKeseluruhan' => $totalSebelum, 
                'totalSesudah' => $totalSesudah,
                'status_approve_realisasi' => $status_approve_realisasi
            ])
            ->rawColumns(['action', 'status_approve_realisasi'])
            ->make(true);
    }

    public function exportPerjalanan()
    {
        return Excel::download(new PerjalananExport, 'perjalanan.xlsx');
    }

    public function export()
    {
        // Download file Excel
        return Excel::download(new PerjalananExport, 'users1.xlsx');
    }

    public function approveRealisasi(Request $request, $id)
    {
        $data = TravelRequest::find($id);

        if (!$data) {
            return response()->json(['gagal' => 'Data tidak ditemukan!']);
        }

        $idrequest = $request->idrequest;

        if ($idrequest != 1 && $idrequest != 2 ) {
            return response()->json(['gagal' => 'Id request tidal valid!']);
        }

        $data->update([
            'status_approve_realisasi' => $idrequest,
        ]);

        $message = ($idrequest == 2)
            ? 'Realisasi berhasil dikirim!'
            : 'Realisasi berhsail dibatalkan!';

        return response()->json([
            'berhasil' => $message,
            'status_approve_realisasi' => $data->status_approve_realisasi
        ]);
    }

    public function exportExcel($id)
    {
        return Excel::download(new RealisasiExcel($id), 'Kasbon_Report.xlsx');
    }

}
