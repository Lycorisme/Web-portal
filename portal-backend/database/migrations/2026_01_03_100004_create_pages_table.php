<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabel untuk halaman statis dinamis - Profil Instansi.
     * Admin bisa membuat halaman: Sejarah, Visi Misi, Struktur Organisasi, dll.
     */
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Judul halaman: "Visi dan Misi", "Sejarah"
            $table->string('slug')->unique(); // URL: visi-misi, sejarah
            $table->longText('content'); // Isi halaman (HTML/Rich Text)
            $table->string('featured_image')->nullable(); // Gambar header halaman
            $table->string('page_type')->default('profile'); // Tipe: profile, contact, about, custom
            $table->string('template')->default('default'); // Template view yang digunakan
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            
            // Settings
            $table->boolean('is_published')->default(false);
            $table->boolean('show_in_menu')->default(true); // Tampil di menu navigasi?
            $table->string('menu_icon')->nullable(); // Icon untuk menu
            $table->unsignedInteger('menu_order')->default(0); // Urutan di menu
            
            // Author
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('slug');
            $table->index('page_type');
            $table->index('is_published');
            $table->index('show_in_menu');
            $table->index('menu_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
