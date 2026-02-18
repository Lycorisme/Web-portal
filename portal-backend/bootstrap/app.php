<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register middleware aliases
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'role' => \App\Http\Middleware\CheckRole::class,
            'security' => \App\Http\Middleware\CheckSecurityAccess::class,
            'maintenance' => \App\Http\Middleware\CheckMaintenanceMode::class,
            'blocked.client' => \App\Http\Middleware\CheckBlockedClient::class,
        ]);

        // Trust all proxies for testing purposes (allows X-Forwarded-For header)
        $middleware->trustProxies(at: '*');
        
        // Apply maintenance check to all public web routes
        $middleware->appendToGroup('web', [
            \App\Http\Middleware\CheckMaintenanceMode::class,
            \App\Http\Middleware\CheckBlockedClient::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

