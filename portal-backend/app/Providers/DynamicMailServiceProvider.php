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
            $smtpHost = SiteSetting::get('smtp_host');
            $smtpPort = SiteSetting::get('smtp_port');
            $smtpUsername = SiteSetting::get('smtp_username');
            $smtpPassword = SiteSetting::get('smtp_password');
            $smtpEncryption = SiteSetting::get('smtp_encryption');
            $mailFromAddress = SiteSetting::get('mail_from_address');
            $mailFromName = SiteSetting::get('mail_from_name');

            // Only override if database settings are configured
            if ($smtpHost && $smtpUsername) {
                // Set the default mailer
                if ($mailDriver) {
                    Config::set('mail.default', $mailDriver);
                }

                // Configure SMTP settings
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
                if ($mailFromAddress) {
                    Config::set('mail.from.address', $mailFromAddress);
                }

                // Set from name (fallback to site name)
                $fromName = $mailFromName ?: SiteSetting::get('site_name', config('app.name'));
                Config::set('mail.from.name', $fromName);
            }

        } catch (\Exception $e) {
            // If database is not ready or settings not available, use .env config
            \Log::warning('DynamicMailServiceProvider: Could not load mail settings from database', [
                'error' => $e->getMessage()
            ]);
        }
    }
}
