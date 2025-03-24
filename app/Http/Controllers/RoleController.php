<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
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
        return view('admin.role.index', ['name' => $this->name]);
    }

    public function getData()
    {
        return DataTables::of(Role::query())
            ->addColumn('action', function ($role) {
                $permissionrole = route('role.permission', ['id' => $role->id]);
                return '
                    <a href="' . $permissionrole . '" class="btn btn-sm btn-info" " data-toggle="tooltip" data-placement="top" title="Permission">
                            <i class="bx bx-check-shield"></i>
                        </a>
                    <button class="btn btn-sm btn-primary edit-btn" data-id="' . $role->id . '" data-toggle="tooltip" data-placement="top" title="Edit">
                        <i class="bx bx-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="' . $role->id . '" data-toggle="tooltip" data-placement="top" title="Hapus">
                        <i class="bx bx-trash"></i>
                    </button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['gagal' => 'Nama role tidak boleh kosong']);
        }

        $role = Role::create([
            'name' => $request->name,
        ]);

        return response()->json(['berhasil' => 'Role berhasil ditambahkan!']);
    }

    public function edit($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['gagal' => 'Role tidak ditemukan!']);
        }

        return response()->json($role);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['gagal' => 'Nama role tidak boleh kosong']);
        }

        $role = Role::find($id);

        if(!$role) {
            return response()->json(['gagal' => 'Role tidak ditemukan']);
        }

        $role->update([
            'name' => $request->name,
        ]);

        return response()->json(['berhasil' => 'Role berhasil diperbarui']);
    }

    public function destroy($id)
    {
        Role::destroy($id);
        return response()->json(['berhasil' => 'Role berhasil dihapus!']);
    }
}
