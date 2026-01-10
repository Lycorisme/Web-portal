<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware khusus untuk memproteksi halaman Keamanan.
 * Hanya Super Admin dan Admin yang dapat mengakses fitur keamanan
 * seperti Activity Log, IP Terblokir, dan pengaturan keamanan lainnya.
 * 
 * Ini adalah layer proteksi tambahan selain route middleware.
 */
class CheckSecurityAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda harus login terlebih dahulu.',
                ], 401);
            }
            return redirect()->route('login');
        }

        // Gunakan method helper dari User model
        if (!$user->canAccessSecurity()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak. Halaman ini hanya dapat diakses oleh Administrator.',
                ], 403);
            }
            
            // Redirect ke dashboard dengan pesan error
            return redirect()
                ->route('dashboard')
                ->with('error', 'Akses ditolak. Halaman keamanan hanya dapat diakses oleh Administrator.');
        }

        return $next($request);
    }
}
