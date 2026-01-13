<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Update SMTP settings to use TLS (port 587) instead of SSL (port 465)
     * This is more reliable for Gmail SMTP connections
     */
    public function up(): void
    {
        // Update smtp_port from 465 to 587 if it's still default
        DB::table('site_settings')
            ->where('key', 'smtp_port')
            ->where('value', '465')
            ->update([
                'value' => '587',
                'description' => 'Port SMTP (587 untuk TLS - Recommended, 465 untuk SSL)',
                'updated_at' => now(),
            ]);

        // Update smtp_encryption from ssl to tls if it's still default
        DB::table('site_settings')
            ->where('key', 'smtp_encryption')
            ->where('value', 'ssl')
            ->update([
                'value' => 'tls',
                'description' => 'Enkripsi SMTP (TLS recommended untuk Gmail)',
                'updated_at' => now(),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to SSL settings
        DB::table('site_settings')
            ->where('key', 'smtp_port')
            ->where('value', '587')
            ->update([
                'value' => '465',
                'description' => 'Port SMTP (465 untuk SSL, 587 untuk TLS)',
                'updated_at' => now(),
            ]);

        DB::table('site_settings')
            ->where('key', 'smtp_encryption')
            ->where('value', 'tls')
            ->update([
                'value' => 'ssl',
                'description' => 'Enkripsi SMTP (ssl/tls/none)',
                'updated_at' => now(),
            ]);
    }
};
