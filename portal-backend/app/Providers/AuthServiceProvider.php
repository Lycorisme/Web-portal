<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Set custom remember me cookie expiration (30 days for government portal security)
        $rememberMeMinutes = config('auth.remember_me_expiration', 43200);
        
        // Configure the remember me cookie duration
        Auth::extend('session', function ($app, $name, array $config) use ($rememberMeMinutes) {
            $provider = Auth::createUserProvider($config['provider'] ?? null);
            
            $guard = new \Illuminate\Auth\SessionGuard(
                $name,
                $provider,
                $app['session.store'],
                request()
            );
            
            // Set the remember me duration in minutes
            $guard->setRememberDuration($rememberMeMinutes);
            
            if (method_exists($guard, 'setCookieJar')) {
                $guard->setCookieJar($app['cookie']);
            }
            
            if (method_exists($guard, 'setDispatcher')) {
                $guard->setDispatcher($app['events']);
            }
            
            if (method_exists($guard, 'setRequest')) {
                $guard->setRequest($app->refresh('request', $guard, 'setRequest'));
            }
            
            return $guard;
        });
    }
}
