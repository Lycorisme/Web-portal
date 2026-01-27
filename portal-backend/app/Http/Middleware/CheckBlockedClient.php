<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\BlockedClient;
use App\Models\ActivityLog;

/**
 * Middleware untuk memblokir akses dari IP yang sudah terdaftar di daftar blokir.
 * Ini adalah lapisan pertahanan pertama sebelum request mencapai controller.
 * 
 * Digunakan pada route sensitif seperti login, register, dan password reset.
 */
class CheckBlockedClient
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah IP terblokir
        $blocked = BlockedClient::byIp($request->ip())
            ->activeBlocks()
            ->first();
            
        if ($blocked) {
            // Log percobaan akses dari IP terblokir
            ActivityLog::create([
                'user_id' => null,
                'action' => 'blocked_access_attempt',
                'description' => "Akses ditolak: IP {$request->ip()} terblokir. Reason: {$blocked->reason}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'level' => ActivityLog::LEVEL_WARNING,
            ]);
            
            // Increment attempt count untuk tracking
            $blocked->incrementAttempt();
            
            // Response JSON untuk API request
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak. IP Anda terblokir sementara karena aktivitas mencurigakan.',
                    'blocked_until' => $blocked->blocked_until?->toIso8601String(),
                ], 403);
            }
            
            // Response HTML untuk web request
            return response()->view('errors.blocked', [
                'reason' => $blocked->reason,
                'blocked_until' => $blocked->blocked_until,
            ], 403);
        }
        
        return $next($request);
    }
}
