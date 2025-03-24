<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use App\Models\PermissionRole;
use Auth;
use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use App\Models\Jabatan;


class KaryawanController extends Controller
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
        $data['permissionAddKaryawan'] = PermissionRole::getPermission('Add Karyawan', Auth::user()->role);
        $jabatan = Jabatan::all();
        $departement = Departement::all(); 
        $user = User::all();
        return view('admin.karyawan.index', [
            'name' => $this->name, 
            'jabatan' => $jabatan,
            'departement' => $departement,
            'user' => $user,
            'data' => $data
        ]);
    }

    public function getData()
    {
        $permissionEditKaryawan = PermissionRole::getPermission('Edit Karyawan', Auth::user()->role);
        $permissionDeleteKaryawan = PermissionRole::getPermission('Delete Karyawan', Auth::user()->role);

        return DataTables::of(Karyawan::with('user', 'jabatan', 'departement'))
            ->addColumn('jabatan', fn($karyawan) => $karyawan->jabatan->name ?? '-')
            ->addColumn('departement', fn($karyawan) => $karyawan->departement->name ?? '-')
            ->addColumn('user', fn($karyawan) => $karyawan->user->name ?? '-')
            ->addColumn('name', fn($karyawan) => $karyawan->user->name ?? '-')
            ->addColumn('email', fn($karyawan) => $karyawan->user->email ?? '-')
            ->addColumn('status_user', fn($karyawan) => $karyawan->user?->status_user == 1 ? 'Akses' : 'Non Akses')
            ->addColumn('action', function ($karyawan) use ($permissionEditKaryawan, $permissionDeleteKaryawan) {
                $editButton = '';
                $deleteButton = '';
                
                if ($permissionEditKaryawan > 0) {
                    $editButton = '
                        <button class="btn btn-sm btn-primary edit-btn" data-id="' . $karyawan->id . '">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                    ';
                }

                if ($permissionDeleteKaryawan > 0) {
                    $deleteButton = '
                        <button class="btn btn-sm btn-danger delete-btn" data-id="' . $karyawan->id . '">
                            <i class="fas fa-trash-alt"></i> Hapus
                        </button>
                    ';
                }

                return $editButton . $deleteButton;
            })
            ->rawColumns(['action'])  
            ->make(true);  
    }


    public function store(Request $request)
    {
        $request->validate([
            'jabatan_id' => 'required',
            'user_id' => 'required',
            'departement_id' => 'required',
            'nomortlp' => 'required',
        ]);

        Karyawan::create([
            'user_id' => $request->user_id,
            'jabatan_id' => $request->jabatan_id,
            'departement_id' => $request->departement_id,
            'nomortlp' => $request->nomortlp,
            'nik' => $request->nik,
        ]);

        $user = User::find($request->user_id);
        $user->update([
            'status_user' => $request->status_user
        ]);

        return response()->json(['berhasil' => 'Karyawan berhasil ditambahkan!']);
    }

    public function edit($id)
    {
        $karyawan = Karyawan::with(['jabatan', 'departement', 'user'])->find($id);

        if (!$karyawan) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        return response()->json([
            'id' => $karyawan->id,
            'user_id' => $karyawan->user_id,
            'jabatan_id' => $karyawan->jabatan_id,
            'departement_id' => $karyawan->departement_id,
            'nomortlp' => $karyawan->nomortlp,
            'nik' => $karyawan->nik,
            'status_user' => $karyawan->user ? intval($karyawan->user->status_user) : 0,
            'name' => $karyawan->user ? $karyawan->user->name : 'Tidak Diketahui',
            'departement_name' => $karyawan->departement ? $karyawan->departement->name : 'Tidak Diketahui',
            'jabatan_name' => $karyawan->jabatan ? $karyawan->jabatan->name : 'Tidak Diketahui',
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required',
            'jabatan_id' => 'required',
            'departement_id' => 'required',
            'status_user' => 'required',
            'nomortlp' => 'required',
        ]);

        $karyawan = Karyawan::find($id);

        if(!$karyawan) {
            return response()->json(['gagal' => 'Karyawan tidak ditemukan']);
        }

        $karyawan->update([
            'user_id' => $request->user_id,
            'jabatan_id' => $request->jabatan_id,
            'departement_id' => $request->departement_id,
            'nomortlp' => $request->nomortlp,
            'nik' => $request->nik,
        ]);

        $user = User::find($request->user_id);
        $user->update([
            'status_user' => $request->status_user
        ]);

        return response()->json(['berhasil' => 'Karyawan berhasil diperbarui']);
    }

    public function destroy($id)
    {
        Karyawan::destroy($id);
        return response()->json(['berhasil' => 'Karyawan berhasil dihapus!']);
    }
}
