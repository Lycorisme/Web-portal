<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Default categories untuk artikel berita.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Berita Utama',
                'slug' => 'berita-utama',
                'description' => 'Berita penting dan terkini dari instansi',
                'color' => '#EF4444', // Red
                'icon' => 'fa-newspaper',
                'sort_order' => 1,
            ],
            [
                'name' => 'Pengumuman',
                'slug' => 'pengumuman',
                'description' => 'Pengumuman resmi dan informasi penting',
                'color' => '#3B82F6', // Blue
                'icon' => 'fa-bullhorn',
                'sort_order' => 2,
            ],
            [
                'name' => 'Agenda Kegiatan',
                'slug' => 'agenda-kegiatan',
                'description' => 'Jadwal dan dokumentasi kegiatan mendatang',
                'color' => '#F59E0B', // Amber
                'icon' => 'fa-calendar-alt',
                'sort_order' => 3,
            ],
            [
                'name' => 'Artikel Edukasi',
                'slug' => 'artikel-edukasi',
                'description' => 'Artikel yang menambah wawasan dan pengetahuan',
                'color' => '#10B981', // Green
                'icon' => 'fa-book-reader',
                'sort_order' => 4,
            ],
            [
                'name' => 'Teknologi',
                'slug' => 'teknologi',
                'description' => 'Perkembangan teknologi dan informasi terkini',
                'color' => '#6366F1', // Indigo
                'icon' => 'fa-laptop-code',
                'sort_order' => 5,
            ],
            [
                'name' => 'Galeri Foto',
                'slug' => 'galeri-foto',
                'description' => 'Kumpulan dokumentasi visual kegiatan',
                'color' => '#8B5CF6', // Purple
                'icon' => 'fa-images',
                'sort_order' => 6,
            ],
            [
                'name' => 'Pemerintahan',
                'slug' => 'pemerintahan',
                'description' => 'Informasi kebijakan dan program pemerintah',
                'color' => '#14B8A6', // Teal
                'icon' => 'fa-building',
                'sort_order' => 7,
            ],
            [
                'name' => 'Pendidikan',
                'slug' => 'pendidikan',
                'description' => 'Info seputar dunia pendidikan dan sekolah',
                'color' => '#EC4899', // Pink
                'icon' => 'fa-graduation-cap',
                'sort_order' => 8,
            ],
            [
                'name' => 'Umum',
                'slug' => 'umum',
                'description' => 'Berita dan informasi umum lainnya',
                'color' => '#6B7280', // Gray
                'icon' => 'fa-info-circle',
                'sort_order' => 99,
            ],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->updateOrInsert(
                ['slug' => $category['slug']],
                array_merge($category, [
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
