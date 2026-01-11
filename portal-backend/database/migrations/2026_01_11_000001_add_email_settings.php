<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Email Settings
        $settings = [
            [
                'key' => 'mail_driver',
                'value' => 'smtp',
                'type' => 'string',
                'group' => 'email',
                'label' => 'Driver Email',
                'description' => 'Metode pengiriman email (smtp atau sendmail)',
                'is_public' => false,
            ],
            [
                'key' => 'smtp_host',
                'value' => '',
                'type' => 'string',
                'group' => 'email',
                'label' => 'SMTP Host',
                'description' => 'Alamat server SMTP (contoh: smtp.gmail.com)',
                'is_public' => false,
            ],
            [
                'key' => 'smtp_port',
                'value' => '465',
                'type' => 'integer',
                'group' => 'email',
                'label' => 'SMTP Port',
                'description' => 'Port SMTP (465 untuk SSL, 587 untuk TLS)',
                'is_public' => false,
            ],
            [
                'key' => 'smtp_username',
                'value' => '',
                'type' => 'string',
                'group' => 'email',
                'label' => 'SMTP Username',
                'description' => 'Username untuk autentikasi SMTP',
                'is_public' => false,
            ],
            [
                'key' => 'smtp_password',
                'value' => '',
                'type' => 'string',
                'group' => 'email',
                'label' => 'SMTP Password',
                'description' => 'Password atau App Password untuk SMTP',
                'is_public' => false,
            ],
            [
                'key' => 'smtp_encryption',
                'value' => 'ssl',
                'type' => 'string',
                'group' => 'email',
                'label' => 'Enkripsi SMTP',
                'description' => 'Metode enkripsi (ssl, tls, atau kosongkan)',
                'is_public' => false,
            ],
            [
                'key' => 'mail_from_address',
                'value' => '',
                'type' => 'string',
                'group' => 'email',
                'label' => 'Alamat Email Pengirim',
                'description' => 'Email yang muncul sebagai pengirim',
                'is_public' => false,
            ],
            [
                'key' => 'mail_from_name',
                'value' => '',
                'type' => 'string',
                'group' => 'email',
                'label' => 'Nama Pengirim',
                'description' => 'Nama yang muncul sebagai pengirim',
                'is_public' => false,
            ],
            [
                'key' => 'otp_expiry_minutes',
                'value' => '10',
                'type' => 'integer',
                'group' => 'email',
                'label' => 'Masa Berlaku OTP (menit)',
                'description' => 'Durasi kode OTP berlaku sebelum kadaluarsa',
                'is_public' => false,
            ],
            [
                'key' => 'otp_max_attempts',
                'value' => '3',
                'type' => 'integer',
                'group' => 'email',
                'label' => 'Maksimal Percobaan OTP',
                'description' => 'Jumlah maksimal percobaan OTP sebelum harus request ulang',
                'is_public' => false,
            ],
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
        DB::table('site_settings')->where('group', 'email')->delete();
    }
};
