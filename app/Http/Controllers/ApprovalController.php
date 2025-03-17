<?php

namespace App\Http\Controllers;

use App\Models\Categoryproduct;
use App\Models\TravelRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ApprovalController extends Controller
{
    protected $name;

    public function __construct()
    {
        $email = session('email');
        if ($email) {
            $this->name = User::where('email', $email)->pluck('name')->first();
        }
    }

    public function dashboard()
    {
        return view('adminapprover.dashboard', ['name' => $this->name]);
    }

    public function index()
    {
        return view('adminapprover.index', ['name' => $this->name]);
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = TravelRequest::with(['user'])->select('*')->get();

            return DataTables::of($data)
                ->addColumn('user_name', function ($data) {
                    return $data->user ? $data->user->name : '-';
                })
                ->addColumn('status_approve', function ($data) {
                    $status = [
                        0 => '<span class="badge bg-label-warning">Diproses</span>',
                        1 => '<span class="badge bg-label-success">Disetujui</span>',
                        2 => '<span class="badge bg-label-danger">Ditolak</span>',
                    ];
                    return $status[$data->status_approve] ?? '<span class="badge badge-secondary">Tidak Diketahui</span>';
                })
                ->addColumn('action', function($data) {
                    return '
                        <button class="btn btn-sm btn-primary edit-btn" data-id="' . $data->id . '">
                            <i class="fas fa-edit"></i> Detail
                        </button>
                    ';
                })
                ->rawColumns(['status_approve','action'])
                ->make(true);
        }
    }

    public function getCategoryProduct()
    {
        $categorypd = Categoryproduct::all();
        return response()->json($categorypd);
    }

    public function getUserpj()
    {
        $userId = session('user_id'); 
        $users = User::where('id', '!=', $userId)->get(); 
        return response()->json($users);
    }

    public function edit($id)
    {
        $travelrequest = TravelRequest::with(['user', 'participants', 'penanggungjawab', 'expenses', 'categorypf.category','karyawan', 'karyawan.jabatan','karyawan.departement'])->find($id);

        if (!$travelrequest) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($travelrequest);
    }

    public function approver(Request $requst)
    {
        
    }

    public function submitRequest(Request $request, $id)
    {
        $request->validate([
            'status_approve'
        ]);
        $detail = TravelRequest::findOrFail($id);

        $detail->update([
            'status_approve' => $request->status_approve
        ]);

        return response()->json(['berhasil' => 'Request berhasil dikirim']);
    }
}
