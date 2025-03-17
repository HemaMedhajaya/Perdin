<?php

namespace App\Http\Controllers;

use App\Models\Biaya;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BiayaController extends Controller
{
    protected $name;

    public function __construct()
    {
        $email = session('email');
        if($email) {
            $this->name = User::where('email', $email)->pluck('name')->first();
        }
    }

    public function index()
    {
        return view('admin.biaya.index', ['name' => $this->name]);
    }

    public function getData()
    {
        $data = Biaya::all();
        return DataTables::of($data)
            ->addColumn('name', function ($data) {
                return $data->name ? $data->name : '-';
            })
            ->addColumn('status', function ($data) {
                return $data->status == 1 ? 'Tambah' : 'Kurang';
            })
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

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'status' => 'required'
        ]);

        Biaya::create($request->all());

        return response()->json(['berhasil' => 'Biaya berhasil ditambahkan!']);
    }

    public function edit($id)
    {
        $biaya = Biaya::find($id);
        return response()->json($biaya);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'status' => 'required'
        ]);

        $biaya = Biaya::find($id);

        if(!$biaya) {
            return response()->json(['gagal' => 'Biaya tidak ditemukan']);
        }
        $biaya->update($request->all());

        return response()->json(['berhasil' => 'Jabatan berhasil biaya']);
    }

    public function destroy($id)
    {
        Biaya::destroy($id);
        return response()->json(['berhasil' => 'Biaya berhasil dihapus!']);
    }
}
