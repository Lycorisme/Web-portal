<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabel untuk Galeri Kegiatan - sesuai menu di Laporan PKL.
     * Menyimpan dokumentasi foto/video kegiatan instansi.
     */
    public function up(): void
    {
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Judul kegiatan: "Upacara HUT RI ke-79"
            $table->text('description')->nullable(); // Deskripsi lengkap kegiatan
            $table->string('image_path'); // Path file gambar: /storage/galleries/xxx.jpg
            $table->string('thumbnail_path')->nullable(); // Path thumbnail untuk loading cepat
            $table->enum('media_type', ['image', 'video'])->default('image'); // Jenis media
            $table->string('video_url')->nullable(); // URL video (YouTube, dll) jika type = video
            $table->string('album')->nullable(); // Pengelompokan album/event
            $table->date('event_date')->nullable(); // Tanggal kegiatan berlangsung
            $table->string('location')->nullable(); // Lokasi kegiatan
            $table->boolean('is_featured')->default(false); // Tampil di homepage?
            $table->boolean('is_published')->default(true); // Status publish
            $table->unsignedInteger('sort_order')->default(0); // Urutan tampilan
            
            // User yang upload
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('album');
            $table->index('event_date');
            $table->index('is_featured');
            $table->index('is_published');
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};
