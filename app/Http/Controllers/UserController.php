<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use App\Models\UserActivityLog;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $name;

    public function __construct()
    {
        $email = session('email');
        if ($email) {
            $this->name = User::where('email', $email)->pluck('name')->first();
        }
        view()->share('name', $this->name);
    }
    public function index()
    {
        return view('admin.user.index');
    }

    public function dashboard()
    {
        return view('welcome', ['name' => $this->name]);
    }

    public function getData()
    {
        return DataTables::of(User::query())
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
            'email' => 'required|email|unique:users',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        UserActivityLog::create([
            'user_id' => Auth::id(), 
            'activity' => 'Tambah', 
            'description' => 'Menambahkan data baru', 
        ]);

        return response()->json(['berhasil' => 'User berhasil ditambahkan!']);
    }

    public function edit($id)
    {
        $user = User::find($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $user = User::find($id);

        if (!$user) {
            return response()->json(['gagal' => 'User tidak ditemukan!'], 404);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        UserActivityLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Edit',
            'description' => 'Mengedit data dengan ID ' . $id,
        ]);

        return response()->json(['berhasil' => 'User berhasil diperbarui!']);
    }

    public function destroy($id)
    {
        User::destroy($id);
        return response()->json(['berhasil' => 'User berhasil dihapus!']);
    }
}
