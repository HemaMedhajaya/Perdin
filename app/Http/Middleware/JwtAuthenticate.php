<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class JwtAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            // Memeriksa apakah token valid
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

        } catch (JWTException $e) {
            // Jika token tidak ditemukan atau terjadi kesalahan dalam memverifikasi token
            return response()->json(['error' => 'Token is invalid or expired'], 401);
        }

        // Lanjutkan ke request berikutnya jika token valid
        return $next($request);
    }
}
