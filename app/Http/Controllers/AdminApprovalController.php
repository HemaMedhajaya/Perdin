<?php

namespace App\Http\Controllers;

use App\Models\MatrixApprovals;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AdminApprovalController extends Controller
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
        return view('admin.adminapproval.index', ['name' => $this->name]);
    }

    public function getData()
    {
        return DataTables::of(MatrixApprovals::query())
            ->addColumn('action', function ($data) {
                return '
                    <button class="btn btn-sm btn-primary edit-btn" data-id="' . $data->id . '">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="' . $data->id . '">
                        <i class="fas fa-trash-alt"></i> Hapus
                    </button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function edit($id)
    {
        $adminapproval = MatrixApprovals::find($id);
        if (!$adminapproval) {
            return response()->json(['gagal' => 'Data tidak ditemukan']);
        }
        $user = User::all();
        return response()->json([
            'user' => $user,
            'adminapproval' => $adminapproval
        ]);
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'udf1' => 'nullable|integer',
            'udf2' => 'nullable|integer',
            'udf3' => 'nullable|integer',
            'udf4' => 'nullable|integer',
            'udf5' => 'nullable|integer',
            'udf6' => 'nullable|integer',
            'udf7' => 'nullable|integer',
            'udf8' => 'nullable|integer',
            'udf9' => 'nullable|integer',
            'udf10' => 'nullable|integer',
        ]);

        $approval = MatrixApprovals::findOrFail($id);

        $approval->update([
            'name' => $request->name,
            'udf1' => $request->udf1,
            'udf2' => $request->udf2,
            'udf3' => $request->udf3,
            'udf4' => $request->udf4,
            'udf5' => $request->udf5,
            'udf6' => $request->udf6,
            'udf7' => $request->udf7,
            'udf8' => $request->udf8,
            'udf9' => $request->udf9,
            'udf10' => $request->udf10,
        ]);

        return response()->json(['berhasil' => 'Data berhasil diperbarui!']);
    }

}
