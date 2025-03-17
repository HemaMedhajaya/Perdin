<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Departement;
use Yajra\DataTables\Facades\DataTables;

class DepartementController extends Controller
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
        return view('admin.departement.index', ['name' => $this->name]);
    }

    public function getData()
    {
        return DataTables::of(Departement::query())
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

        Departement::create([
            'name' => $request->name,
        ]);

        return response()->json(['berhasil'=> 'Departement berhasil ditambahkan!']);
    }

    public function edit($id)
    {
        $Departement = Departement::find($id);
        return response()->json($Departement);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $departement = Departement::find($id);

        if(!$departement) {
            return response()->json(['gagal' => 'Departement tidak ditemukan']);
        }

        $departement->update([
            'name' => $request->name,
        ]);

        return response()->json(['berhasil' => 'Departement berhasil diperbarui']);
    }

    public function destroy($id)
    {
        Departement::destroy($id);
        return response()->json(['berhasil' => 'Departement berhasil dihapus!']);
    }
}
