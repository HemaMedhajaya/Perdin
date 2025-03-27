<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SubMenu;
use App\Models\PermissionRole;
use App\Models\Permission;

class CheckPermission
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $roleId = $user->role; // Role ID dari user yang login
        $routeName = $request->route()->getName(); 
        
        // 2. Cek apakah route ada di tabel submenu
        $submenu = SubMenu::where('route', $routeName)->first();
        
        if (!$submenu) {
            return redirect()->back()->with('gagal', 'Akses ditolak! Submenu tidak ditemukan.');
        }
        
        // dd($submenu->type);
        // 3. Cek apakah role ID user sesuai dengan `type` pada submenu
        if ($submenu->type != $roleId) {
            return redirect()->back()->with('gagal', 'Akses ditolak! Anda tidak memiliki izin untuk submenu ini.');
        }
        
        // 4. Cek apakah role user memiliki permission untuk submenu ini
        $permission = Permission::where('submenu_id', $submenu->id)->first();
        

        if ($permission) {
            $hasPermission = PermissionRole::where('role_id', $roleId)
                ->where('permission_id', $permission->id)
                ->exists();
                // dd($hasPermission);
            if (!$hasPermission) {
                return redirect()->back()->with('gagal', 'Akses ditolak! Anda tidak memiliki izin.');
            }
        }
        return $next($request);
    }
}
