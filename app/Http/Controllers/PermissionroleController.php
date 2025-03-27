<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\Role;
use App\Models\SubMenu;
use App\Models\User;
use Illuminate\Http\Request;

class PermissionroleController extends Controller
{
    protected $name;

    public function __construct()
    {
        $email = session('email');
        if ($email) {
            $this->name = User::where('email', $email)->pluck('name')->first();
        }
    }

    public function index($id)
    {
        $role = Role::findOrFail($id);
    
        // Ambil semua submenu_id yang memiliki type sesuai dengan role_id
        $submenuIds = SubMenu::where('type', $id)->pluck('id')->toArray();
    
        // Ambil semua permission_id yang ada dalam submenu yang cocok
        $availablePermissions = Permission::whereIn('submenu_id', $submenuIds)
            ->pluck('id')
            ->toArray();
    
        // Ambil permission_id yang SUDAH diberikan ke role ini (untuk centang)
        $assignedPermissions = PermissionRole::where('role_id', $id)
            ->whereIn('permission_id', $availablePermissions) // Hanya yang masuk dalam submenu_id yang sesuai
            ->pluck('permission_id')
            ->toArray();
    
        // Ambil semua groupby dari permission yang sesuai
        $groupByList = Permission::whereIn('id', $availablePermissions)
            ->pluck('groupby')
            ->unique()
            ->toArray();
    
        // Ambil semua permission yang memiliki groupby yang sama
        $permissions = Permission::whereIn('groupby', $groupByList)
            ->get()
            ->groupBy('groupby');
    
        $result = [];
        foreach ($permissions as $groupby => $permissionGroup) {
            // Ambil permission pertama untuk mendapatkan nama utama dari grup
            $firstPermission = $permissionGroup->first();
    
            $data = [];
            $data['id'] = $firstPermission->id;  // ID dari permission pertama dalam grup
            $data['name'] = $firstPermission->name; // Nama dari permission pertama dalam grup
            $data['groupby'] = $groupby; // Nama grup
            $data['group'] = [];
    
            foreach ($permissionGroup as $permission) {
                $data['group'][] = [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'checked' => in_array($permission->id, $assignedPermissions) // Centang jika ada di assignedPermissions
                ];
            }
    
            $result[] = $data;
        }
    
        return view('admin.permissionrole.index', [
            'name' => $this->name,
            'data' => $result,
            'id' => $id,
            'assignedPermissions' => $assignedPermissions
        ]);
    }
    



    public function togglePermission(Request $request)
    {
        $request->validate([
            'role_id' => 'required|integer',
            'permission_id' => 'required|integer',
            'action' => 'required|string'
        ]);

        if ($request->action == 'add') {
            // Simpan data baru jika belum ada
            PermissionRole::firstOrCreate([
                'role_id' => $request->role_id,
                'permission_id' => $request->permission_id
            ]);

            return response()->json(['message' => 'Permission added successfully.']);
        } else {
            // Hapus data jika di-uncheck
            PermissionRole::where('role_id', $request->role_id)
                ->where('permission_id', $request->permission_id)
                ->delete();

            return response()->json(['message' => 'Permission removed successfully.']);
        }
    }

}
