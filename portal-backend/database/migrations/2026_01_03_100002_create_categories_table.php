<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabel master untuk kategori artikel.
     * Menggantikan kolom category (string) di articles agar lebih terstruktur.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama kategori: "Berita Utama", "Pengumuman", dll
            $table->string('slug')->unique(); // URL-friendly: berita-utama, pengumuman
            $table->text('description')->nullable(); // Deskripsi kategori
            $table->string('color', 20)->nullable(); // Warna untuk badge: #FF5733, blue, dll
            $table->string('icon', 50)->nullable(); // Icon class: fa-newspaper, mdi-news
            $table->unsignedInteger('sort_order')->default(0); // Urutan tampilan
            $table->boolean('is_active')->default(true); // Aktif/non-aktif
            $table->timestamps();
            $table->softDeletes(); // deleted_at untuk soft delete
            
            $table->index('slug');
            $table->index('is_active');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
