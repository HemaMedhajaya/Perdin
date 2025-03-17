<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\SubMenu;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SubMenuController extends Controller
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
        return view('admin.submenu.index', ['name' => $this->name]);
    }

    public function getData()
    {
        $subMenus = SubMenu::with('menu');
        return DataTables::of($subMenus)
            ->addColumn('menu_name', function ($subMenu) {
                return $subMenu->menu ? $subMenu->menu->name : '-';
            })
            ->addColumn('type_label', function ($subMenu) {
                return $subMenu->type == 1 ? 'Admin' : 'User';
            })
            ->addColumn('action', function($subMenu) {
                return '
                    <button class="btn btn-sm btn-primary edit-btn" data-id="' . $subMenu->id . '">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="' . $subMenu->id . '">
                        <i class="fas fa-trash-alt"></i> Hapus
                    </button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getMenu()
    {
        $menus = Menu::all();
        return response()->json($menus);

    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'route' => 'required',
            'menu_id' => 'required',
            'type' => 'required'
        ]);

        SubMenu::create($request->all());
        
        return response()->json(['berhasil' => 'Submenu berhasil ditambahkan!']);
    }

    public function edit(string $id)
    {
        $submenu = SubMenu::with('menu')->find($id);
        if (!$submenu) {
            return response()->json(['gagal' => 'Data tidak ditemukan!']);
        }
        $menus = Menu::all();

        return response()->json([
            'submenu' => $submenu,
            'menus' => $menus,
        ]);
    }


    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'route' => 'required',
            'menu_id' => 'required',
            'type' => 'required'
        ]);

        $submenu = SubMenu::find($id);
        if(!$submenu) {
            return response()->json(['gagal' => 'Submenu tidak ditemukan!']);
        }
        $submenu->update($request->all());

        return response()->json(['berhasil' => 'Submenu berhasil diperbarui']);
    }

    public function destroy(string $id)
    {
        SubMenu::destroy($id);
        return response()->json(['berhasil' => 'Submenu berhasil dihapus!']);
    }
}
