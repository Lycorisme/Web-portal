<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, boolean, integer, json, text
            $table->string('group')->default('general'); // general, seo, appearance, security, social, media
            $table->string('label')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(false);
            $table->timestamps();
        });

        // Insert default settings
        $settings = [
            // General Settings
            ['key' => 'site_name', 'value' => '', 'type' => 'string', 'group' => 'general', 'label' => 'Nama Portal', 'description' => 'Nama utama website', 'is_public' => true],
            ['key' => 'site_tagline', 'value' => '', 'type' => 'string', 'group' => 'general', 'label' => 'Tagline', 'description' => 'Slogan atau tagline website', 'is_public' => true],
            ['key' => 'site_email', 'value' => '', 'type' => 'string', 'group' => 'general', 'label' => 'Email Redaksi', 'description' => 'Email utama untuk kontak', 'is_public' => true],
            ['key' => 'site_phone', 'value' => '', 'type' => 'string', 'group' => 'general', 'label' => 'Nomor Telepon', 'description' => 'Nomor telepon kantor redaksi', 'is_public' => true],
            ['key' => 'site_address', 'value' => '', 'type' => 'text', 'group' => 'general', 'label' => 'Alamat Redaksi', 'description' => 'Alamat lengkap kantor redaksi', 'is_public' => true],
            ['key' => 'site_description', 'value' => '', 'type' => 'text', 'group' => 'general', 'label' => 'Deskripsi Website', 'description' => 'Deskripsi singkat tentang website', 'is_public' => true],

            // SEO Settings
            ['key' => 'meta_title', 'value' => '', 'type' => 'string', 'group' => 'seo', 'label' => 'Meta Title', 'description' => 'Judul yang muncul di hasil pencarian', 'is_public' => true],
            ['key' => 'meta_description', 'value' => '', 'type' => 'text', 'group' => 'seo', 'label' => 'Meta Description', 'description' => 'Deskripsi untuk hasil pencarian Google', 'is_public' => true],
            ['key' => 'meta_keywords', 'value' => '', 'type' => 'string', 'group' => 'seo', 'label' => 'Meta Keywords', 'description' => 'Kata kunci untuk SEO (pisahkan dengan koma)', 'is_public' => true],
            ['key' => 'google_analytics_id', 'value' => '', 'type' => 'string', 'group' => 'seo', 'label' => 'Google Analytics ID', 'description' => 'ID untuk tracking Google Analytics', 'is_public' => false],

            // Social Media
            ['key' => 'facebook_url', 'value' => '', 'type' => 'string', 'group' => 'social', 'label' => 'Facebook URL', 'description' => 'Link halaman Facebook', 'is_public' => true],
            ['key' => 'twitter_url', 'value' => '', 'type' => 'string', 'group' => 'social', 'label' => 'Twitter URL', 'description' => 'Link profil Twitter/X', 'is_public' => true],
            ['key' => 'instagram_url', 'value' => '', 'type' => 'string', 'group' => 'social', 'label' => 'Instagram URL', 'description' => 'Link profil Instagram', 'is_public' => true],
            ['key' => 'youtube_url', 'value' => '', 'type' => 'string', 'group' => 'social', 'label' => 'YouTube URL', 'description' => 'Link channel YouTube', 'is_public' => true],
            ['key' => 'linkedin_url', 'value' => '', 'type' => 'string', 'group' => 'social', 'label' => 'LinkedIn URL', 'description' => 'Link profil LinkedIn', 'is_public' => true],

            // Appearance Settings
            ['key' => 'theme_color', 'value' => '#0f172a', 'type' => 'string', 'group' => 'appearance', 'label' => 'Warna Utama', 'description' => 'Warna utama tema (HEX)', 'is_public' => true],
            ['key' => 'accent_color', 'value' => '#3b82f6', 'type' => 'string', 'group' => 'appearance', 'label' => 'Warna Aksen', 'description' => 'Warna aksen untuk tombol dan link', 'is_public' => true],
            ['key' => 'sidebar_color', 'value' => '#0f172a', 'type' => 'string', 'group' => 'appearance', 'label' => 'Warna Sidebar', 'description' => 'Warna background sidebar', 'is_public' => false],
            ['key' => 'current_theme', 'value' => 'corporate', 'type' => 'string', 'group' => 'appearance', 'label' => 'Tema Aktif', 'description' => 'Preset tema yang aktif', 'is_public' => false],

            // Security Settings
            ['key' => 'rate_limit_per_minute', 'value' => '60', 'type' => 'integer', 'group' => 'security', 'label' => 'Rate Limit', 'description' => 'Batas request per menit untuk API', 'is_public' => false],
            ['key' => 'auto_ban_enabled', 'value' => 'true', 'type' => 'boolean', 'group' => 'security', 'label' => 'Auto Ban', 'description' => 'Aktifkan auto-ban untuk IP spam', 'is_public' => false],
            ['key' => 'maintenance_mode', 'value' => 'false', 'type' => 'boolean', 'group' => 'security', 'label' => 'Maintenance Mode', 'description' => 'Aktifkan mode pemeliharaan', 'is_public' => true],

            // Media Settings
            ['key' => 'favicon_url', 'value' => '', 'type' => 'string', 'group' => 'media', 'label' => 'Favicon', 'description' => 'URL atau path favicon website', 'is_public' => true],
            ['key' => 'logo_url', 'value' => '', 'type' => 'string', 'group' => 'media', 'label' => 'Logo Utama', 'description' => 'URL atau path logo utama', 'is_public' => true],
            ['key' => 'letterhead_url', 'value' => '', 'type' => 'string', 'group' => 'media', 'label' => 'Kop Surat', 'description' => 'URL atau path gambar kop surat', 'is_public' => false],
            ['key' => 'signature_url', 'value' => '', 'type' => 'string', 'group' => 'media', 'label' => 'Tanda Tangan Digital', 'description' => 'URL atau path tanda tangan digital', 'is_public' => false],
            ['key' => 'stamp_url', 'value' => '', 'type' => 'string', 'group' => 'media', 'label' => 'Stempel', 'description' => 'URL atau path gambar stempel', 'is_public' => false],
        ];

        foreach ($settings as $setting) {
            DB::table('site_settings')->insert(array_merge($setting, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
