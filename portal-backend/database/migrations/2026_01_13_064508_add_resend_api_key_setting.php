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
        // Add resend_api_key setting if not exists
        $exists = DB::table('site_settings')->where('key', 'resend_api_key')->exists();
        
        if (!$exists) {
            DB::table('site_settings')->insert([
                'key' => 'resend_api_key',
                'value' => '',
                'group' => 'email',
                'type' => 'text',
                'description' => 'API Key dari Resend.com untuk pengiriman email via HTTP API',
                'is_public' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('site_settings')->where('key', 'resend_api_key')->delete();
    }
};
