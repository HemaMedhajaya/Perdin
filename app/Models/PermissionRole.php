<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionRole extends Model
{
    protected $table = 'permission_role';

    protected $fillable = [
        'role_id',
        'permission_id',
    ];

    static public function getPermission($slug, $role_id)
    {
        return PermissionRole::select('permission_role.id')
                    ->join('permissions', 'permissions.id', '=', 'permission_role.permission_id')
                    ->where('permission_role.role_id', '=', $role_id)
                    ->where('permissions.slug', '=', $slug)
                    ->count();
    }
}
