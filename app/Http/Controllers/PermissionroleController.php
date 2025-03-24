<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\Role;
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
        $role = Role    ::findOrFail($id);
        $assignedPermissions = PermissionRole::where('role_id', $id)->pluck('permission_id')->toArray(); 
        $permission = Permission::groupBy('groupby')->get();
        $result = array();
        foreach($permission as $value) {
            $permissiongroup = Permission::permissiongroup($value->groupby);
            $data = array();
            $data['id'] = $value->id;
            $data['name'] = $value->name;
            $group = array();
            foreach ($permissiongroup as $valueG) {
                $dataG = array();
                $dataG['id'] = $valueG->id;
                $dataG['name'] = $valueG->name;
                $group[] = $dataG;
            }
            $data['group'] = $group;
            $result[] = $data;
        }
        return view('admin.permissionrole.index', ['name' => $this->name, 'data' => $result, 'id' => $id, 'assignedPermissions' => $assignedPermissions]);
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
