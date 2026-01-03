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
                'color' => '#EF4444',
                'icon' => 'fa-newspaper',
                'sort_order' => 1,
            ],
            [
                'name' => 'Pengumuman',
                'slug' => 'pengumuman',
                'description' => 'Pengumuman resmi dan informasi penting',
                'color' => '#F59E0B',
                'icon' => 'fa-bullhorn',
                'sort_order' => 2,
            ],
            [
                'name' => 'Kegiatan',
                'slug' => 'kegiatan',
                'description' => 'Dokumentasi dan liputan kegiatan',
                'color' => '#10B981',
                'icon' => 'fa-calendar-check',
                'sort_order' => 3,
            ],
            [
                'name' => 'Artikel',
                'slug' => 'artikel',
                'description' => 'Artikel edukatif dan informatif',
                'color' => '#3B82F6',
                'icon' => 'fa-file-alt',
                'sort_order' => 4,
            ],
            [
                'name' => 'Opini',
                'slug' => 'opini',
                'description' => 'Opini dan pandangan',
                'color' => '#8B5CF6',
                'icon' => 'fa-comment-dots',
                'sort_order' => 5,
            ],
            [
                'name' => 'Umum',
                'slug' => 'umum',
                'description' => 'Berita dan informasi umum lainnya',
                'color' => '#6B7280',
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
