<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\SubMenu;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
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
        $submenu = SubMenu::all();
        return view('admin.permission.index', ['name' => $this->name, 'submenu' => $submenu]);
    }

    public function getData()
    {
        return DataTables::of(Permission::query())
            ->addColumn('action', function ($permission) {
                return '
                    <button class="btn btn-sm btn-primary edit-btn" data-id="' . $permission->id . '">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="' . $permission->id . '">
                        <i class="fas fa-trash-alt"></i> Hapus
                    </button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required',
            'submenu_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['gagal' => 'Nama role tidak boleh kosong']);
        }

        $submenu_id = $request->submenu_id;

// Cek apakah submenu_id sudah ada pada tabel Permission
$existingGroup = Permission::where('submenu_id', $submenu_id)
                            ->orderBy('groupby', 'desc')
                            ->first();

if ($existingGroup) {
    // Jika ada, set groupby berdasarkan nilai tertinggi yang ada
    $groupby = $existingGroup->groupby;
} else {
    // Jika submenu_id baru, set groupby menjadi 1
    $groupby = 1;
}

// Cek apakah submenu_id berbeda dari sebelumnya
$previousSubmenuId = Permission::where('groupby', '>', 0)
                                ->orderBy('groupby', 'desc')
                                ->first();

if ($previousSubmenuId && $previousSubmenuId->submenu_id !== $submenu_id) {
    // Jika submenu_id berubah, naikkan groupby
    $groupby = Permission::where('submenu_id', '!=', $submenu_id)->max('groupby') + 1;
}

// Menyimpan data permission dengan groupby yang sesuai
$permission = Permission::create([
    'name' => $request->name,
    'slug' => $request->slug,
    'groupby' => $groupby,
    'submenu_id' => $submenu_id,
]);


        return response()->json(['berhasil' => 'Permission berhasil ditambahkan!']);
    }

    public function edit($id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json(['gagal' => 'Permission tidak ditemukan!']);
        }

        return response()->json($permission);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'groupby' => 'required',
            'slug' => 'required',
            'submenu_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['gagal' => 'Permission tidak boleh kosong']);
        }

        $permission = Permission::find($id);

        if(!$permission) {
            return response()->json(['gagal' => 'Permission tidak ditemukan']);
        }

        $permission->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'groupby' => $request->groupby,
            'submenu_id' => $request->submenu_id
        ]);

        return response()->json(['berhasil' => 'Permission berhasil diperbarui']);
    }

    public function destroy($id)
    {
        Permission::destroy($id);
        return response()->json(['berhasil' => 'Permission berhasil dihapus!']);
    }
}
