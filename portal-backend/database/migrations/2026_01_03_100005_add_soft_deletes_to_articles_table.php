<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Menambahkan soft delete dan category_id ke tabel articles.
     * - deleted_at: untuk fitur Trash (Soft Delete)
     * - category_id: relasi ke tabel categories (menggantikan kolom category string)
     */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            // Soft Delete column
            $table->softDeletes()->after('updated_at');
            
            // Foreign key ke categories (nullable untuk backward compatibility)
            $table->foreignId('category_id')
                  ->nullable()
                  ->after('category')
                  ->constrained('categories')
                  ->nullOnDelete();
            
            // Index untuk query soft delete cepat
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
            $table->dropSoftDeletes();
        });
    }
};
