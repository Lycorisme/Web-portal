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
            // Letterhead / Kop Surat Settings - Hierarki Instansi
            [
                'key' => 'letterhead_parent_org_1',
                'value' => '',
                'type' => 'string',
                'group' => 'letterhead',
                'label' => 'Hierarki Instansi 1',
                'description' => 'Instansi induk tingkat pertama (contoh: PEMERINTAH PROVINSI JAWA BARAT)',
                'is_public' => false
            ],
            [
                'key' => 'letterhead_parent_org_2',
                'value' => '',
                'type' => 'string',
                'group' => 'letterhead',
                'label' => 'Hierarki Instansi 2',
                'description' => 'Instansi induk tingkat kedua (contoh: DINAS PENDIDIKAN DAN KEBUDAYAAN)',
                'is_public' => false
            ],
            [
                'key' => 'letterhead_org_name',
                'value' => '',
                'type' => 'string',
                'group' => 'letterhead',
                'label' => 'Nama Organisasi',
                'description' => 'Nama resmi organisasi/instansi untuk kop surat (contoh: BALAI TEKNOLOGI INFORMASI DAN KOMUNIKASI PENDIDIKAN (BTIKP))',
                'is_public' => false
            ],

            // Letterhead / Kop Surat Settings - Alamat Lengkap
            [
                'key' => 'letterhead_street',
                'value' => '',
                'type' => 'string',
                'group' => 'letterhead',
                'label' => 'Alamat Jalan',
                'description' => 'Nama jalan lengkap tanpa singkatan (contoh: Jalan Raya Utama No. 123)',
                'is_public' => false
            ],
            [
                'key' => 'letterhead_district',
                'value' => '',
                'type' => 'string',
                'group' => 'letterhead',
                'label' => 'Kelurahan/Kecamatan',
                'description' => 'Nama kelurahan atau kecamatan',
                'is_public' => false
            ],
            [
                'key' => 'letterhead_city',
                'value' => '',
                'type' => 'string',
                'group' => 'letterhead',
                'label' => 'Kota/Kabupaten',
                'description' => 'Nama kota atau kabupaten',
                'is_public' => false
            ],
            [
                'key' => 'letterhead_province',
                'value' => '',
                'type' => 'string',
                'group' => 'letterhead',
                'label' => 'Provinsi',
                'description' => 'Nama provinsi',
                'is_public' => false
            ],
            [
                'key' => 'letterhead_postal_code',
                'value' => '',
                'type' => 'string',
                'group' => 'letterhead',
                'label' => 'Kode Pos',
                'description' => 'Kode pos alamat (5 digit)',
                'is_public' => false
            ],

            // Letterhead / Kop Surat Settings - Kontak
            [
                'key' => 'letterhead_phone',
                'value' => '',
                'type' => 'string',
                'group' => 'letterhead',
                'label' => 'Nomor Telepon',
                'description' => 'Nomor telepon kantor dengan kode area (contoh: (021) 1234567)',
                'is_public' => false
            ],
            [
                'key' => 'letterhead_fax',
                'value' => '',
                'type' => 'string',
                'group' => 'letterhead',
                'label' => 'Nomor Faksimili',
                'description' => 'Nomor fax/faksimili kantor dengan kode area',
                'is_public' => false
            ],
            [
                'key' => 'letterhead_email',
                'value' => '',
                'type' => 'string',
                'group' => 'letterhead',
                'label' => 'Email Resmi',
                'description' => 'Alamat email resmi instansi dengan domain resmi (contoh: admin@btikp.go.id)',
                'is_public' => false
            ],
            [
                'key' => 'letterhead_website',
                'value' => '',
                'type' => 'string',
                'group' => 'letterhead',
                'label' => 'Website',
                'description' => 'Alamat website resmi instansi (contoh: www.btikp.cloud)',
                'is_public' => false
            ],
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
            'letterhead_parent_org_1',
            'letterhead_parent_org_2',
            'letterhead_org_name',
            'letterhead_street',
            'letterhead_district',
            'letterhead_city',
            'letterhead_province',
            'letterhead_postal_code',
            'letterhead_phone',
            'letterhead_fax',
            'letterhead_email',
            'letterhead_website',
        ];

        DB::table('site_settings')->whereIn('key', $keys)->delete();
    }
};
