<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            'BTIKP',
            'Dikbud',
            'Kalsel',
            'Berita Terkini',
            'Info Pendidikan',
            'Teknologi Informasi',
            'Digitalisasi',
            'Workshop',
            'Pelatihan',
            'Prestasi',
            'Kurikulum Merdeka',
            'Guru Penggerak',
            'Siswa Berprestasi',
            'E-Learning',
            'Smart School'
        ];

        foreach ($tags as $tagName) {
            $slug = Str::slug($tagName);
            
            DB::table('tags')->updateOrInsert(
                ['slug' => $slug],
                [
                    'name' => $tagName,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                    // deleted_at is null by default
                ]
            );
        }
    }
}
