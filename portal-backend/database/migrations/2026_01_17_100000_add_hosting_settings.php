<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $settings = [
            // Hosting/Deployment Settings
            ['key' => 'hosting_app_url', 'value' => '', 'type' => 'string', 'group' => 'hosting', 'label' => 'URL Aplikasi', 'description' => 'URL production website (contoh: https://yoursite.infinityfree.com)', 'is_public' => false],
            ['key' => 'hosting_app_name', 'value' => '', 'type' => 'string', 'group' => 'hosting', 'label' => 'Nama Aplikasi', 'description' => 'Nama aplikasi untuk environment production', 'is_public' => false],
            ['key' => 'hosting_app_env', 'value' => 'production', 'type' => 'string', 'group' => 'hosting', 'label' => 'Environment', 'description' => 'Environment aplikasi (production/staging)', 'is_public' => false],
            ['key' => 'hosting_app_debug', 'value' => 'false', 'type' => 'boolean', 'group' => 'hosting', 'label' => 'Debug Mode', 'description' => 'Aktifkan debug mode (matikan di production!)', 'is_public' => false],
            
            // Database Settings
            ['key' => 'hosting_db_connection', 'value' => 'mysql', 'type' => 'string', 'group' => 'hosting', 'label' => 'Koneksi Database', 'description' => 'Tipe koneksi database', 'is_public' => false],
            ['key' => 'hosting_db_host', 'value' => '', 'type' => 'string', 'group' => 'hosting', 'label' => 'Database Host', 'description' => 'Host database MySQL (contoh: sql###.infinityfree.com)', 'is_public' => false],
            ['key' => 'hosting_db_port', 'value' => '3306', 'type' => 'string', 'group' => 'hosting', 'label' => 'Database Port', 'description' => 'Port database (default: 3306)', 'is_public' => false],
            ['key' => 'hosting_db_name', 'value' => '', 'type' => 'string', 'group' => 'hosting', 'label' => 'Nama Database', 'description' => 'Nama database MySQL', 'is_public' => false],
            ['key' => 'hosting_db_user', 'value' => '', 'type' => 'string', 'group' => 'hosting', 'label' => 'Database User', 'description' => 'Username database', 'is_public' => false],
            ['key' => 'hosting_db_password', 'value' => '', 'type' => 'encrypted', 'group' => 'hosting', 'label' => 'Database Password', 'description' => 'Password database (terenkripsi)', 'is_public' => false],
            
            // FTP Settings  
            ['key' => 'hosting_ftp_host', 'value' => '', 'type' => 'string', 'group' => 'hosting', 'label' => 'FTP Host', 'description' => 'Host FTP untuk upload file', 'is_public' => false],
            ['key' => 'hosting_ftp_user', 'value' => '', 'type' => 'string', 'group' => 'hosting', 'label' => 'FTP User', 'description' => 'Username FTP', 'is_public' => false],
            ['key' => 'hosting_ftp_password', 'value' => '', 'type' => 'encrypted', 'group' => 'hosting', 'label' => 'FTP Password', 'description' => 'Password FTP (terenkripsi)', 'is_public' => false],
            
            // Additional Settings
            ['key' => 'hosting_mail_mailer', 'value' => 'smtp', 'type' => 'string', 'group' => 'hosting', 'label' => 'Mail Driver', 'description' => 'Driver untuk mengirim email', 'is_public' => false],
            ['key' => 'hosting_notes', 'value' => '', 'type' => 'text', 'group' => 'hosting', 'label' => 'Catatan Deployment', 'description' => 'Catatan tambahan untuk deployment', 'is_public' => false],
        ];

        foreach ($settings as $setting) {
            // Check if setting already exists
            $exists = DB::table('site_settings')->where('key', $setting['key'])->exists();
            
            if (!$exists) {
                DB::table('site_settings')->insert(array_merge($setting, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $keys = [
            'hosting_app_url',
            'hosting_app_name',
            'hosting_app_env',
            'hosting_app_debug',
            'hosting_db_connection',
            'hosting_db_host',
            'hosting_db_port',
            'hosting_db_name',
            'hosting_db_user',
            'hosting_db_password',
            'hosting_ftp_host',
            'hosting_ftp_user',
            'hosting_ftp_password',
            'hosting_mail_mailer',
            'hosting_notes',
        ];

        DB::table('site_settings')->whereIn('key', $keys)->delete();
    }
};
