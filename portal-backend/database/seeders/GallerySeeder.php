<?php

namespace Database\Seeders;

use App\Models\Gallery;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Menggunakan gambar yang sudah ada di storage/articles/thumbnails
     */
    public function run(): void
    {
        // Get admin user for uploaded_by
        $admin = User::where('email', 'admin@btikp.go.id')->first() 
                ?? User::first();
        $uploadedBy = $admin?->id;

        // Path ke folder thumbnails artikel
        $thumbnailPath = storage_path('app/public/articles/thumbnails');
        
        // Ambil semua file gambar yang ada
        $imageFiles = [];
        if (File::isDirectory($thumbnailPath)) {
            $imageFiles = File::files($thumbnailPath);
        }

        // Konversi ke array nama file
        $images = [];
        foreach ($imageFiles as $file) {
            $images[] = $file->getFilename();
        }

        // Jika tidak ada gambar, return
        if (empty($images)) {
            $this->command->warn('Tidak ada gambar di storage/articles/thumbnails. Skip seeder galeri.');
            return;
        }

        // Data dummy galeri kegiatan
        $galleries = [
            [
                'title' => 'Upacara Peringatan Hari Kemerdekaan RI ke-80',
                'description' => 'Upacara bendera dalam rangka memperingati Hari Kemerdekaan Republik Indonesia ke-80 yang diselenggarakan di halaman kantor BTIKP. Acara diikuti oleh seluruh pegawai dan pejabat struktural.',
                'album' => 'Upacara & Seremonial',
                'event_date' => '2025-08-17',
                'location' => 'Halaman Kantor BTIKP',
                'is_featured' => true,
                'is_published' => true,
            ],
            [
                'title' => 'Workshop Transformasi Digital Pelayanan Publik',
                'description' => 'Kegiatan workshop dalam rangka meningkatkan pemahaman dan keterampilan ASN dalam implementasi transformasi digital pada pelayanan publik. Peserta berasal dari berbagai instansi pemerintah daerah.',
                'album' => 'Workshop & Pelatihan',
                'event_date' => '2025-09-15',
                'location' => 'Aula Gedung BTIKP',
                'is_featured' => true,
                'is_published' => true,
            ],
            [
                'title' => 'Rapat Koordinasi Tim Pengembangan Aplikasi',
                'description' => 'Rapat koordinasi rutin tim pengembangan aplikasi untuk membahas progress pengembangan sistem informasi dan evaluasi kinerja bulanan.',
                'album' => 'Rapat & Koordinasi',
                'event_date' => '2025-10-05',
                'location' => 'Ruang Rapat Utama BTIKP',
                'is_featured' => false,
                'is_published' => true,
            ],
            [
                'title' => 'Sosialisasi Keamanan Siber untuk Instansi Pemerintah',
                'description' => 'Kegiatan sosialisasi tentang pentingnya keamanan siber dan langkah-langkah pencegahan serangan cyber untuk seluruh instansi pemerintah di wilayah kerja.',
                'album' => 'Sosialisasi',
                'event_date' => '2025-11-12',
                'location' => 'Aula Serbaguna',
                'is_featured' => true,
                'is_published' => true,
            ],
            [
                'title' => 'Pelatihan Pengelolaan Website dan Konten Digital',
                'description' => 'Pelatihan intensif bagi admin website instansi pemerintah dalam mengelola konten website yang informatif dan menarik untuk pelayanan publik.',
                'album' => 'Workshop & Pelatihan',
                'event_date' => '2025-11-20',
                'location' => 'Laboratorium Komputer BTIKP',
                'is_featured' => false,
                'is_published' => true,
            ],
            [
                'title' => 'Kunjungan Kerja Pejabat Kementerian Komunikasi',
                'description' => 'Kunjungan kerja pejabat Kementerian Komunikasi dan Informatika dalam rangka monitoring dan evaluasi program digitalisasi daerah.',
                'album' => 'Kunjungan Kerja',
                'event_date' => '2025-12-03',
                'location' => 'Kantor BTIKP',
                'is_featured' => true,
                'is_published' => true,
            ],
            [
                'title' => 'Gathering Akhir Tahun BTIKP 2025',
                'description' => 'Acara gathering dan refleksi akhir tahun untuk seluruh pegawai BTIKP. Kegiatan diisi dengan berbagai perlombaan dan hiburan.',
                'album' => 'Gathering & Hiburan',
                'event_date' => '2025-12-20',
                'location' => 'The Jayakarta Resort',
                'is_featured' => true,
                'is_published' => true,
            ],
            [
                'title' => 'Launching Aplikasi E-Government Terpadu',
                'description' => 'Acara peluncuran resmi aplikasi E-Government terpadu yang mengintegrasikan seluruh layanan pemerintah dalam satu platform digital.',
                'album' => 'Launching & Peresmian',
                'event_date' => '2026-01-08',
                'location' => 'Aula Gedung BTIKP',
                'is_featured' => true,
                'is_published' => true,
            ],
            [
                'title' => 'Audit Sistem Informasi Tahunan',
                'description' => 'Kegiatan audit sistem informasi tahunan yang dilakukan oleh tim auditor internal untuk memastikan keamanan dan kehandalan sistem.',
                'album' => 'Audit & Monitoring',
                'event_date' => '2026-01-10',
                'location' => 'Ruang Server BTIKP',
                'is_featured' => false,
                'is_published' => true,
            ],
            [
                'title' => 'Forum Diskusi Inovasi Pelayanan Publik',
                'description' => 'Forum diskusi antar instansi untuk berbagi pengalaman dan best practice dalam inovasi pelayanan publik berbasis teknologi.',
                'album' => 'Forum & Diskusi',
                'event_date' => '2026-01-12',
                'location' => 'Convention Hall Lantai 3',
                'is_featured' => false,
                'is_published' => true,
            ],
        ];

        // Insert data galeri
        foreach ($galleries as $index => $data) {
            // Pilih gambar secara rotasi dari gambar yang tersedia
            $imageIndex = $index % count($images);
            $imagePath = 'articles/thumbnails/' . $images[$imageIndex];

            Gallery::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'image_path' => $imagePath,
                'thumbnail_path' => $imagePath,
                'media_type' => 'image',
                'video_url' => null,
                'album' => $data['album'],
                'event_date' => Carbon::parse($data['event_date']),
                'location' => $data['location'],
                'is_featured' => $data['is_featured'],
                'is_published' => $data['is_published'],
                'sort_order' => $index + 1,
                'uploaded_by' => $uploadedBy,
                'published_at' => Carbon::parse($data['event_date']),
            ]);
        }

        $this->command->info('Berhasil menambahkan ' . count($galleries) . ' data galeri kegiatan.');
    }
}
