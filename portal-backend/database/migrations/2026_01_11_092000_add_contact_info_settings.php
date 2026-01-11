<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan pengaturan untuk informasi kontak dan statis
     */
    public function up(): void
    {
        $settings = [
            // Informasi Statis / Profil
            [
                'key' => 'site_history',
                'value' => '',
                'type' => 'text',
                'group' => 'contact',
                'label' => 'Sejarah Singkat',
                'description' => 'Teks sejarah singkat organisasi/instansi',
                'is_public' => true,
            ],
            [
                'key' => 'site_vision_mission',
                'value' => '',
                'type' => 'text',
                'group' => 'contact',
                'label' => 'Visi & Misi',
                'description' => 'Poin-poin visi dan misi organisasi',
                'is_public' => true,
            ],
            
            // Kontak Tambahan
            [
                'key' => 'whatsapp_number',
                'value' => '',
                'type' => 'string',
                'group' => 'contact',
                'label' => 'Nomor WhatsApp',
                'description' => 'Nomor WhatsApp untuk kontak langsung (format: 6281234567890)',
                'is_public' => true,
            ],
            
            // Google Maps Embed
            [
                'key' => 'site_map_code',
                'value' => '',
                'type' => 'text',
                'group' => 'contact',
                'label' => 'Kode Embed Google Maps',
                'description' => 'Kode embed iframe Google Maps untuk menampilkan lokasi kantor',
                'is_public' => true,
            ],
            
            // TikTok Social Media
            [
                'key' => 'tiktok_url',
                'value' => '',
                'type' => 'string',
                'group' => 'contact',
                'label' => 'TikTok URL',
                'description' => 'Link profil TikTok',
                'is_public' => true,
            ],
        ];

        foreach ($settings as $setting) {
            $exists = DB::table('site_settings')->where('key', $setting['key'])->exists();
            
            if (!$exists) {
                DB::table('site_settings')->insert(array_merge($setting, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
        
        // Update group untuk social media yang ada agar masuk ke tab Kontak
        DB::table('site_settings')
            ->whereIn('key', ['facebook_url', 'twitter_url', 'instagram_url', 'youtube_url', 'linkedin_url'])
            ->update(['group' => 'contact']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus setting baru
        DB::table('site_settings')->whereIn('key', [
            'site_history',
            'site_vision_mission',
            'whatsapp_number',
            'site_map_code',
            'tiktok_url',
        ])->delete();
        
        // Kembalikan social media ke group asli
        DB::table('site_settings')
            ->whereIn('key', ['facebook_url', 'twitter_url', 'instagram_url', 'youtube_url', 'linkedin_url'])
            ->update(['group' => 'social']);
    }
};
