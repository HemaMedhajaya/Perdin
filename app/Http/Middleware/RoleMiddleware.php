<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        $roles = [
            'superadmin' => 1,
            'admin' => 2,
            'user' => 3
        ];
        if (!Auth::check() || Auth::user()->role !== $roles[$role]) {
            return back()->with('gagal', 'Akses ditolak! Anda tidak memiliki izin.');
        }
        return $next($request);
    }
}
