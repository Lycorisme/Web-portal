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
        // Add signature and stamp size settings
        $settings = [
            [
                'key' => 'signature_size',
                'value' => '80', // Default 80px (matches current ttd-image height)
                'type' => 'integer',
                'group' => 'signature',
                'label' => 'Ukuran Tanda Tangan',
                'description' => 'Ukuran tinggi tanda tangan dalam pixel (40-150px)',
                'is_public' => false,
            ],
            [
                'key' => 'stamp_size',
                'value' => '85', // Default 85px (matches current ttd-stamp height)
                'type' => 'integer',
                'group' => 'signature',
                'label' => 'Ukuran Stempel',
                'description' => 'Ukuran tinggi stempel dalam pixel (40-150px)',
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
        DB::table('site_settings')
            ->whereIn('key', ['signature_size', 'stamp_size'])
            ->delete();
    }
};
