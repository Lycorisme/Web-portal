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
            // Signature / Mandatum Settings - Tembusan
            [
                'key' => 'signature_cc',
                'value' => '',
                'type' => 'text', // Multiline text
                'group' => 'signature',
                'label' => 'Tembusan Surat',
                'description' => 'Daftar pihak yang mendapat tembusan surat (pisahkan dengan baris baru)',
                'is_public' => false
            ],
            // Update existing leader settings to be in 'signature' group for better organization in UI
            // We don't delete/re-insert, just conceptually we will use them in the new tab.
        ];

        foreach ($settings as $setting) {
            // Check if key already exists
            $exists = DB::table('site_settings')->where('key', $setting['key'])->exists();

            if (!$exists) {
                DB::table('site_settings')->insert(array_merge($setting, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
        
        // Update group for existing leader settings to 'signature' to group them properly
        DB::table('site_settings')
            ->whereIn('key', ['leader_name', 'leader_title', 'leader_nip', 'signature_url', 'stamp_url'])
            ->update(['group' => 'signature']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert group changes
        DB::table('site_settings')
            ->whereIn('key', ['leader_name', 'leader_title', 'leader_nip'])
            ->update(['group' => 'organization']);
            
        DB::table('site_settings')
            ->whereIn('key', ['signature_url', 'stamp_url'])
            ->update(['group' => 'media']);

        // Delete new keys
        DB::table('site_settings')->where('key', 'signature_cc')->delete();
    }
};
