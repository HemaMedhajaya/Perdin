<?php

namespace App\Http\Controllers;

use App\Models\TravelExpense;
use App\Models\TravelPenanggungjawab;
use App\Models\TravelRequest;
use App\Models\User;
use App\Models\UserMatrixApprovals;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DetailApproverControler extends Controller
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
        $travelRequest = TravelRequest::find($id);
        $nameProject = $travelRequest ? $travelRequest->name_project : 'Tidak Diketahui';

        $penanggungJawab = TravelPenanggungjawab::where('travel_request_id', $id)->with('user')->first();
        $nameUser = $penanggungJawab && $penanggungJawab->user ? $penanggungJawab->user->name : 'Tidak Diketahui';
        return view('adminapprover.detail', [
            'name' => $this->name,
            'nameprojec' => $nameProject,
            'nameuser' => $nameUser,
            'id' => $id,
            'status_approver' => $travelRequest->status_approve
        ]);
    }

    public function getDataDetailApprover($id)
    {
        $userid = session('user_id');
        $data = TravelExpense::with('travelRequest')->where('travel_request_id', $id)->get();
        $total = $data->sum('total');

        return DataTables::of($data)
            ->addColumn('jenis_perjalanan', function ($data) {
                return $data->jenis_perjalanan == 1 ? 'Transportasi' : 'Akomodasi';
            })
            ->addColumn('description', function ($data) {
                return $data->description ? $data->description : '-';
            })
            ->addColumn('total', function ($data) {
                return $data->total ? $data->total : '-';
            })
            ->addColumn('status_approve', function ($data) use ($userid) {
                $userApproval = UserMatrixApprovals::where('id_user', $userid)
                    ->where('id_perdin', $data->travel_request_id)
                    ->where('id_matrix', 1)
                    ->first();
            
                if ($userApproval->status == 'Approve') {
                       return '<span class="badge bg-label-success">Disetujui</span>';
                }
            
                $status = [
                    0 => '<span class="badge bg-label-secondary">Draft</span>',
                    5 => '<span class="badge bg-label-warning">Diproses</span>',
                    1 => '<span class="badge bg-label-success">Disetujui</span>',
                    2 => '<span class="badge bg-label-danger">Ditolak</span>',
                ];
            
                $approvalStatus = $data->travelRequest->status_approve ?? 0;
            
                return $status[$approvalStatus] ?? '<span class="badge badge-secondary">Tidak Diketahui</span>';
            })
            
            
            ->addColumn('action', function ($data) {
                return '
                <button class="btn btn-sm btn-info detail-btn" data-id="' . $data->id . '" data-toggle="tooltip" data-placement="top" title="Edit">
                    <i class="bx bx-detail"></i>
                </button>
                ';
            })
            ->with('totalKeseluruhan', $total)
            ->with('status_approve', $data->isEmpty() ? 0 : ($data->first()->travelRequest->status_approve ?? 0))
            ->rawColumns(['status_approve', 'action'])
            ->make(true);
    }


    public function editdetail($id)
    {
        $detail = TravelExpense::find($id);
        return response()->json($detail);
    }
    
    public function updateTravelRequest(Request $request, $id)
    {
        $data = TravelRequest::find($id);
        
        if (!$data) {
            return response()->json(['gagal' => 'Data tidak ditemukan!']);
        }

        $updateData = ['status_approve' => $request->status_approve];
        
        if ($request->status_approve == 2) {
            $updateData['comentar'] = $request->comentar;
            $data->update($updateData);
        }

        if ($request->status_approve == 1) {
            $updateData['status_approve_realisasi'] = 1;
            $userid = session('user_id');
            $usermatrixapproval = UserMatrixApprovals::where('id_user', $userid)
                ->where('status', 'Not Yet')
                ->where('id_perdin', $id)
                ->first();
    
            if ($usermatrixapproval) {
                // Cek apakah dia number terakhir untuk id_perdin = $id
                $isLastApproval = !UserMatrixApprovals::where('id_perdin', $id)
                    ->where('number', '>', $usermatrixapproval->number) // Ada approval dengan number lebih tinggi
                    ->where('status', 'Not Yet') // Masih dalam status "Not Yet"
                    ->exists();
    
                if ($isLastApproval) {
                    // Jika dia yang terakhir, update $data
                    $data->update($updateData);
                    $usermatrixapproval->update([
                        'status' => 'Approve'
                    ]);
                } else {
                    // Jika bukan yang terakhir, update hanya status di UserMatrixApprovals
                    $usermatrixapproval->update([
                        'status' => 'Approve'
                    ]);
                }
            }
        }
        $message = $request->status_approve == 1 ? 'Perjalanan dinas berhasil disetujui!' : 'Perjalanan dinas berhasil ditolak!';
        
        return response()->json(['berhasil' => $message]);
    }

    public function cekStatusApprove($id)
    {
        $request = TravelRequest::find($id);
        $userid = session('user_id');

        $userApproval = UserMatrixApprovals::where('id_user', $userid)
                    ->where('id_perdin', $request->id)
                    ->where('id_matrix', 1)
                    ->first();
        
        if ($request) {
            return response()->json(['status_approve' => $request->status_approve, 'userApproval' => $userApproval->status]);
        }

        return response()->json(['status_approve' => 0]); // Default ke 0 jika data tidak ditemukan
    }

}
