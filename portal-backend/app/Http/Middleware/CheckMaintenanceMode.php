<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\SiteSetting;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Routes that should be accessible even in maintenance mode
     */
    protected array $except = [
        'login',
        'login/*',
        'logout',
        'dashboard',
        'dashboard/*',
        'settings',
        'settings/*',
        'profile',
        'profile/*',
        'articles',
        'articles/*',
        'categories',
        'categories/*',
        'tags',
        'tags/*',
        'galleries',
        'galleries/*',
        'users',
        'users/*',
        'activity-log',
        'activity-log/*',
        'blocked-clients',
        'blocked-clients/*',
        'trash',
        'trash/*',
        'reports',
        'reports/*',
        'p/maintenance', // The maintenance page itself
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if maintenance mode is enabled
        $maintenanceMode = SiteSetting::get('maintenance_mode', false);

        if ($maintenanceMode) {
            // Allow authenticated admin users to access all routes
            if (auth()->check() && in_array(auth()->user()->role ?? '', ['super_admin', 'admin', 'editor', 'author'])) {
                return $next($request);
            }

            // Check if current route is in the except list
            foreach ($this->except as $pattern) {
                if ($request->is($pattern)) {
                    return $next($request);
                }
            }

            // Redirect to maintenance page
            return redirect()->route('public.maintenance');
        }

        return $next($request);
    }
}
