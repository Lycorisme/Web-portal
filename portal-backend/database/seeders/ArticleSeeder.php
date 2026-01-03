<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\User;
use App\Models\Category;
use Carbon\Carbon;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        $categories = Category::all();

        if (!$user) {
            $this->command->info('No user found, skipping article seeder.');
            return;
        }

        $articles = [
            [
                'title' => 'Peringatan HUT BTIKP ke-15: Komitmen Transformasi Digital',
                'excerpt' => 'BTIKP merayakan hari ulang tahun ke-15 dengan berbagai kegiatan yang menunjukkan komitmen terhadap transformasi digital di lingkungan pemerintahan.',
                'content' => '<p>BTIKP merayakan hari ulang tahun ke-15 dengan berbagai kegiatan yang menunjukkan komitmen terhadap transformasi digital di lingkungan pemerintahan.</p><p>Dalam perayaan ini, BTIKP menggelar berbagai acara meliputi seminar, workshop, dan pameran teknologi yang dihadiri oleh berbagai pejabat pemerintahan.</p><p>Kepala BTIKP dalam sambutannya menegaskan bahwa transformasi digital bukan lagi pilihan tetapi keharusan untuk meningkatkan pelayanan publik.</p>',
                'status' => 'published',
                'views' => 1234,
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Workshop Keamanan Siber untuk ASN Provinsi',
                'excerpt' => 'BTIKP mengadakan workshop keamanan siber yang diikuti oleh 200 ASN dari berbagai instansi pemerintahan.',
                'content' => '<p>BTIKP mengadakan workshop keamanan siber yang diikuti oleh 200 ASN dari berbagai instansi pemerintahan provinsi.</p><p>Workshop ini bertujuan untuk meningkatkan kesadaran tentang keamanan siber di era digital.</p>',
                'status' => 'draft',
                'views' => 0,
                'published_at' => null,
            ],
            [
                'title' => 'Launching Aplikasi Layanan Publik Terintegrasi',
                'excerpt' => 'Gubernur meresmikan aplikasi layanan publik terintegrasi yang dikembangkan oleh tim BTIKP.',
                'content' => '<p>Gubernur meresmikan aplikasi layanan publik terintegrasi yang dikembangkan oleh tim BTIKP untuk memberikan kemudahan akses layanan bagi masyarakat.</p><p>Aplikasi ini menggabungkan berbagai layanan publik dalam satu platform yang mudah diakses.</p>',
                'status' => 'published',
                'views' => 3567,
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Program Digitalisasi Arsip Pemerintah Daerah',
                'excerpt' => 'BTIKP meluncurkan program digitalisasi arsip untuk mempermudah pengelolaan dokumen pemerintah.',
                'content' => '<p>Program digitalisasi arsip pemerintah daerah telah diluncurkan sebagai bagian dari upaya modernisasi administrasi pemerintahan.</p><p>Program ini akan mengonversi ribuan dokumen fisik menjadi format digital yang lebih mudah diakses dan dikelola.</p>',
                'status' => 'pending',
                'views' => 0,
                'published_at' => null,
            ],
            [
                'title' => 'Pelatihan Pengembangan Aplikasi Mobile untuk OPD',
                'excerpt' => 'BTIKP menyelenggarakan pelatihan pengembangan aplikasi mobile bagi pegawai dari berbagai OPD.',
                'content' => '<p>Pelatihan pengembangan aplikasi mobile diselenggarakan selama 5 hari untuk meningkatkan kapasitas SDM di bidang teknologi informasi.</p><p>Para peserta mempelajari dasar-dasar pemrograman mobile serta best practices dalam pengembangan aplikasi.</p>',
                'status' => 'published',
                'views' => 892,
                'published_at' => now()->subDays(7),
            ],
            [
                'title' => 'Infrastruktur Jaringan Fiber Optik Diperluas',
                'excerpt' => 'Pemerintah memperluas jangkauan infrastruktur jaringan fiber optik ke wilayah-wilayah terpencil.',
                'content' => '<p>Perluasan jaringan fiber optik merupakan bagian dari program pemerataan akses internet di seluruh wilayah provinsi.</p><p>Dengan infrastruktur ini, diharapkan masyarakat di daerah terpencil dapat menikmati layanan internet berkecepatan tinggi.</p>',
                'status' => 'published',
                'views' => 2134,
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'Sosialisasi Keamanan Data Pribadi',
                'excerpt' => 'BTIKP mengadakan sosialisasi tentang pentingnya menjaga keamanan data pribadi di era digital.',
                'content' => '<p>Sosialisasi ini bertujuan untuk meningkatkan kesadaran masyarakat tentang pentingnya menjaga keamanan data pribadi saat menggunakan layanan digital.</p><p>Berbagai tips praktis dibagikan kepada peserta untuk melindungi informasi personal mereka.</p>',
                'status' => 'draft',
                'views' => 0,
                'published_at' => null,
            ],
            [
                'title' => 'Implementasi Smart City di Ibu Kota Provinsi',
                'excerpt' => 'Program smart city mulai diimplementasikan di ibu kota provinsi dengan berbagai layanan cerdas.',
                'content' => '<p>Implementasi smart city mencakup berbagai aspek seperti transportasi cerdas, pengelolaan sampah pintar, dan sistem monitoring lingkungan.</p><p>Program ini diharapkan dapat meningkatkan kualitas hidup warga kota.</p>',
                'status' => 'published',
                'views' => 4521,
                'published_at' => now()->subDays(15),
            ],
        ];

        foreach ($articles as $index => $article) {
            // Assign random category
            $category = $categories->count() > 0 ? $categories->random() : null;
            
            Article::updateOrCreate(
                ['title' => $article['title']],
                array_merge($article, [
                    'author_id' => $user->id,
                    'category_id' => $category?->id,
                    'category' => $category?->name ?? 'Umum',
                    'read_time' => rand(3, 10),
                    'security_status' => 'passed',
                    'created_at' => now()->subDays($index * 2),
                    'updated_at' => now()->subDays($index),
                ])
            );
        }

        $this->command->info('Articles seeded successfully!');
    }
}
