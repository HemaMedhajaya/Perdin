<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\TravelExpense;
use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class JabatanController extends Controller
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
        return view('admin.jabatan.index', ['name' => $this->name]);
    }

    public function getData()
    {
        return DataTables::of(Jabatan::query())
            ->addColumn('action', function ($user) {
                return '
                    <button class="btn btn-sm btn-primary edit-btn" data-id="' . $user->id . '">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="' . $user->id . '">
                        <i class="fas fa-trash-alt"></i> Hapus
                    </button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        Jabatan::create([
            'name' => $request->name,
        ]);

        return response()->json(['berhasil'=> 'Jabatan berhasil ditambahkan!']);
    }

    public function edit($id)
    {
        $jabatan = Jabatan::find($id);
        return response()->json($jabatan);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $karyawan = Jabatan::find($id);

        if(!$karyawan) {
            return response()->json(['gagal' => 'Jabatan tidak ditemukan']);
        }

        $karyawan->update([
            'name' => $request->name,
        ]);

        return response()->json(['berhasil' => 'Jabatan berhasil diperbarui']);
    }

    public function destroy($id)
    {
        Jabatan::destroy($id);
        return response()->json(['berhasil' => 'Jabatan berhasil dihapus!']);
    }

}
