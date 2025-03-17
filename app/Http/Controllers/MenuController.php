<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class MenuController extends Controller
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
        return view('admin.menu.index', ['name' => $this->name]);
    }

    public function getData()
    {
        return DataTables::of(Menu::query())
            ->addColumn('action', function ($menu) {
               return '
                    <button class="btn btn-sm btn-primary edit-btn" data-id="' . $menu->id . '">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="' . $menu->id . '">
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
            'type' => 'required',
            'route' => 'nullable',
            'icon' => 'nullable',
            'is_parent' => 'required'
        ]);
        Menu::create($request->all());

        return response()->json(['berhasil' => 'Menu berhasil ditambahkan!']);
    }
 
    public function edit(string $id)
    {
        $menus = Menu::find($id);
        return response()->json($menus);
    }

  
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required',
            'route' => 'nullable',
            'icon' => 'nullable',
            'is_parent' => 'required'
        ]);

        $menus = Menu::find($id);
        if(!$menus) {
            return response()->json(['gagal' => 'Menu tidak ditemukan']);
        }

        $menus->update($request->all());

        return response()->json(['berhasil' => 'Menu berhasil diperbarui']);
    }


    public function destroy(string $id)
    {
        Menu::destroy($id);
        return response()->json(['berhasil' => 'Menu berhasil dihapus!']);
    }
}
