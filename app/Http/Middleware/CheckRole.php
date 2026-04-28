<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth; 
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException; 

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        try {
            // Mengambil data user berdasarkan token yang dikirim
            $user = JWTAuth::parseToken()->authenticate();

            // Cek apakah role user ada di dalam daftar parameter $roles
            if (!in_array($user->role, $roles)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            return $next($request);
        } catch (JWTException $e) {
            // Jika token tidak ada, salah, atau expired
            return response()->json([
                'success' => false,
                'message' => 'Token is invalid or expired'
            ], 401);
        }
    }
}