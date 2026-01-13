<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class DynamicMailServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     * Configure mail settings from database at runtime.
     */
    public function boot(): void
    {
        // Only configure if site_settings table exists
        if (!Schema::hasTable('site_settings')) {
            return;
        }

        try {
            // Get mail settings from database
            $mailDriver = SiteSetting::get('mail_driver');
            
            // Skip if no driver configured
            if (!$mailDriver) {
                return;
            }

            // Set the default mailer
            Config::set('mail.default', $mailDriver);

            // Get common settings
            $mailFromAddress = SiteSetting::get('mail_from_address');
            $mailFromName = SiteSetting::get('mail_from_name') ?: SiteSetting::get('site_name', config('app.name'));

            // Configure based on driver type
            if ($mailDriver === 'resend') {
                // Resend driver - uses HTTP API
                $resendApiKey = SiteSetting::get('resend_api_key');
                
                if ($resendApiKey) {
                    // Set environment variable for Resend SDK
                    putenv("RESEND_API_KEY={$resendApiKey}");
                    $_ENV['RESEND_API_KEY'] = $resendApiKey;
                    $_SERVER['RESEND_API_KEY'] = $resendApiKey;
                    
                    // Also set in config
                    Config::set('resend.api_key', $resendApiKey);
                    
                    // Set from address (use Resend default if not set)
                    $fromAddress = $mailFromAddress ?: 'onboarding@resend.dev';
                    Config::set('mail.from.address', $fromAddress);
                    Config::set('mail.from.name', $mailFromName);
                }
                
            } elseif ($mailDriver === 'smtp') {
                // SMTP driver
                $smtpHost = SiteSetting::get('smtp_host');
                $smtpPort = SiteSetting::get('smtp_port');
                $smtpUsername = SiteSetting::get('smtp_username');
                $smtpPassword = SiteSetting::get('smtp_password');
                $smtpEncryption = SiteSetting::get('smtp_encryption');

                if ($smtpHost && $smtpUsername) {
                    Config::set('mail.mailers.smtp.host', $smtpHost);
                    
                    if ($smtpPort) {
                        Config::set('mail.mailers.smtp.port', (int) $smtpPort);
                    }
                    
                    Config::set('mail.mailers.smtp.username', $smtpUsername);
                    Config::set('mail.mailers.smtp.password', $smtpPassword);

                    // Handle encryption
                    if ($smtpEncryption === 'none' || $smtpEncryption === '') {
                        Config::set('mail.mailers.smtp.encryption', null);
                    } else {
                        Config::set('mail.mailers.smtp.encryption', $smtpEncryption);
                    }

                    // Set from address
                    $fromAddress = $mailFromAddress ?: $smtpUsername;
                    Config::set('mail.from.address', $fromAddress);
                    Config::set('mail.from.name', $mailFromName);
                }
                
            } elseif ($mailDriver === 'log') {
                // Log driver - for development
                $fromAddress = $mailFromAddress ?: 'test@example.com';
                Config::set('mail.from.address', $fromAddress);
                Config::set('mail.from.name', $mailFromName);
            }

        } catch (\Exception $e) {
            // If database is not ready or settings not available, use .env config
            \Log::warning('DynamicMailServiceProvider: Could not load mail settings from database', [
                'error' => $e->getMessage()
            ]);
        }
    }
}
