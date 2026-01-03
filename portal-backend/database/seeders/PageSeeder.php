<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Default pages untuk Profil Instansi.
     */
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Visi dan Misi',
                'slug' => 'visi-misi',
                'content' => '<h2>Visi</h2>
<p>Menjadi institusi terdepan dalam pelayanan publik yang profesional, transparan, dan berintegritas.</p>

<h2>Misi</h2>
<ol>
    <li>Memberikan pelayanan prima kepada masyarakat</li>
    <li>Meningkatkan kualitas sumber daya manusia</li>
    <li>Menerapkan tata kelola yang baik dan bersih</li>
    <li>Mengembangkan inovasi dalam pelayanan</li>
    <li>Membangun kemitraan dengan berbagai pihak</li>
</ol>',
                'page_type' => 'profile',
                'menu_order' => 1,
                'menu_icon' => 'fa-bullseye',
            ],
            [
                'title' => 'Sejarah',
                'slug' => 'sejarah',
                'content' => '<h2>Sejarah Singkat</h2>
<p>Instansi ini didirikan pada tahun [TAHUN] dengan semangat untuk memberikan pelayanan terbaik kepada masyarakat.</p>

<h3>Perjalanan Kami</h3>
<p>[Silakan isi sejarah lengkap instansi Anda di sini melalui panel admin]</p>',
                'page_type' => 'profile',
                'menu_order' => 2,
                'menu_icon' => 'fa-history',
            ],
            [
                'title' => 'Struktur Organisasi',
                'slug' => 'struktur-organisasi',
                'content' => '<h2>Struktur Organisasi</h2>
<p>Berikut adalah struktur organisasi instansi kami:</p>

<p>[Silakan upload gambar struktur organisasi atau isi detail struktur melalui panel admin]</p>',
                'page_type' => 'profile',
                'menu_order' => 3,
                'menu_icon' => 'fa-sitemap',
            ],
            [
                'title' => 'Tugas dan Fungsi',
                'slug' => 'tugas-dan-fungsi',
                'content' => '<h2>Tugas Pokok</h2>
<p>[Isi tugas pokok instansi]</p>

<h2>Fungsi</h2>
<ol>
    <li>[Fungsi 1]</li>
    <li>[Fungsi 2]</li>
    <li>[Fungsi 3]</li>
</ol>',
                'page_type' => 'profile',
                'menu_order' => 4,
                'menu_icon' => 'fa-tasks',
            ],
            [
                'title' => 'Kontak Kami',
                'slug' => 'kontak',
                'content' => '<h2>Hubungi Kami</h2>
<p>Silakan hubungi kami melalui:</p>

<h3>Alamat</h3>
<p>[Alamat lengkap akan diambil dari Site Settings]</p>

<h3>Telepon & Email</h3>
<p>Telepon: [Dari Site Settings]</p>
<p>Email: [Dari Site Settings]</p>

<h3>Jam Operasional</h3>
<p>Senin - Jumat: 08:00 - 16:00 WIB</p>
<p>Sabtu - Minggu: Libur</p>',
                'page_type' => 'contact',
                'menu_order' => 10,
                'menu_icon' => 'fa-envelope',
            ],
        ];

        foreach ($pages as $page) {
            DB::table('pages')->updateOrInsert(
                ['slug' => $page['slug']],
                array_merge($page, [
                    'is_published' => true,
                    'show_in_menu' => true,
                    'template' => 'default',
                    'published_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
