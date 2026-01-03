<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabel untuk Audit Trail - mencatat semua aktivitas user di sistem.
     * Berguna untuk keamanan dan tracking siapa melakukan apa.
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            
            // User yang melakukan aksi (nullable jika sistem atau guest)
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            
            // Jenis aksi: CREATE, UPDATE, DELETE, LOGIN, LOGIN_FAILED, LOGOUT, VIEW, EXPORT, dll
            $table->string('action', 50);
            
            // Deskripsi detail aksi
            $table->text('description');
            
            // Polymorphic relation ke model yang di-log (Article, User, Setting, dll)
            $table->string('subject_type')->nullable(); // e.g., App\Models\Article
            $table->unsignedBigInteger('subject_id')->nullable(); // ID dari data yang diubah
            
            // Data sebelum dan sesudah perubahan (untuk undo/audit)
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            
            // Info request
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->string('url')->nullable(); // URL yang diakses saat aksi terjadi
            
            // Severity level: info, warning, danger, critical
            $table->string('level', 20)->default('info');
            
            $table->timestamp('created_at')->useCurrent();
            
            // Indexes untuk query cepat
            $table->index('user_id');
            $table->index('action');
            $table->index(['subject_type', 'subject_id']);
            $table->index('created_at');
            $table->index('level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
