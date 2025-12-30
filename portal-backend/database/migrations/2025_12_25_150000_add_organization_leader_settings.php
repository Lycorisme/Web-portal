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
            // Organization Settings
            ['key' => 'organization_type', 'value' => 'private', 'type' => 'string', 'group' => 'organization', 'label' => 'Tipe Organisasi', 'description' => 'Jenis organisasi: government, private, ngo, other', 'is_public' => false],
            
            // Leader Info
            ['key' => 'leader_name', 'value' => '', 'type' => 'string', 'group' => 'organization', 'label' => 'Nama Pimpinan', 'description' => 'Nama lengkap pimpinan organisasi', 'is_public' => false],
            ['key' => 'leader_title', 'value' => '', 'type' => 'string', 'group' => 'organization', 'label' => 'Jabatan Pimpinan', 'description' => 'Jabatan resmi pimpinan', 'is_public' => false],
            ['key' => 'leader_nip', 'value' => '', 'type' => 'string', 'group' => 'organization', 'label' => 'NIP', 'description' => 'Nomor Induk Pegawai (untuk PNS)', 'is_public' => false],
            ['key' => 'leader_nik', 'value' => '', 'type' => 'string', 'group' => 'organization', 'label' => 'NIK', 'description' => 'Nomor Induk Kependudukan', 'is_public' => false],
            ['key' => 'leader_custom_id', 'value' => '', 'type' => 'string', 'group' => 'organization', 'label' => 'ID Lainnya', 'description' => 'Nomor identitas custom', 'is_public' => false],
            ['key' => 'leader_custom_id_label', 'value' => '', 'type' => 'string', 'group' => 'organization', 'label' => 'Label ID Custom', 'description' => 'Label untuk field ID custom', 'is_public' => false],
            ['key' => 'leader_phone', 'value' => '', 'type' => 'string', 'group' => 'organization', 'label' => 'No. Telepon Pimpinan', 'description' => 'Nomor telepon pimpinan', 'is_public' => false],
            ['key' => 'leader_email', 'value' => '', 'type' => 'string', 'group' => 'organization', 'label' => 'Email Pimpinan', 'description' => 'Email pimpinan', 'is_public' => false],
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $keys = [
            'organization_type',
            'leader_name',
            'leader_title',
            'leader_nip',
            'leader_nik',
            'leader_custom_id',
            'leader_custom_id_label',
            'leader_phone',
            'leader_email',
        ];

        DB::table('site_settings')->whereIn('key', $keys)->delete();
    }
};
