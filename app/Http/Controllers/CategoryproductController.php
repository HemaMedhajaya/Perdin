<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Categoryproduct;
use Yajra\DataTables\Facades\DataTables;


class CategoryproductController extends Controller
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
        return view('admin.categoryproduct.index', ['name' => $this->name]);
    }

    public function getData()
    {
        return DataTables::of(Categoryproduct::query())
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

        Categoryproduct::create([
            'name' => $request->name,
        ]);

        return response()->json(['berhasil'=> 'Categoryproduct berhasil ditambahkan!']);
    }

    public function edit($id)
    {
        $categoryproduct = Categoryproduct::find($id);
        return response()->json($categoryproduct);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $categorypd = Categoryproduct::find($id);

        if(!$categorypd) {
            return response()->json(['gagal' => 'Categoryproduct tidak ditemukan']);
        }

        $categorypd->update([
            'name' => $request->name,
        ]);

        return response()->json(['berhasil' => 'Categoryproduct berhasil diperbarui']);
    }

    public function destroy($id)
    {
        Categoryproduct::destroy($id);
        return response()->json(['berhasil' => 'Categoryproduct berhasil dihapus!']);
    }
}
