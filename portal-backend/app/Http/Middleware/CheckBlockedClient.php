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
 * Admin/Super Admin yang sudah login dikecualikan agar tetap bisa mengelola blokir.
 * Route pengelolaan blocked-clients juga dikecualikan.
 */
class CheckBlockedClient
{
    /**
     * Routes yang dikecualikan dari pengecekan blokir IP.
     * Admin harus tetap bisa mengakses halaman ini untuk mengelola blokir.
     */
    protected array $excludedPaths = [
        'blocked-clients',
        'blocked-clients/*',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip pengecekan untuk route pengelolaan blocked-clients
        if ($this->isExcludedPath($request)) {
            return $next($request);
        }

        // Skip pengecekan untuk admin/super_admin yang sudah login
        // Mereka harus tetap bisa mengakses sistem untuk mengelola blokir
        $user = $request->user();
        if ($user && in_array($user->role, ['super_admin', 'admin'])) {
            return $next($request);
        }

        // Cek apakah IP terblokir
        $blocked = BlockedClient::byIp($request->ip())
            ->activeBlocks()
            ->first();
            
        if ($blocked) {
            // Log percobaan akses dari IP terblokir
            ActivityLog::create([
                'user_id' => $user?->id,
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

    /**
     * Cek apakah request path termasuk yang dikecualikan.
     */
    protected function isExcludedPath(Request $request): bool
    {
        foreach ($this->excludedPaths as $path) {
            if ($request->is($path)) {
                return true;
            }
        }
        return false;
    }
}

