<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tabel untuk fitur custom Rate Limiting.
     * Admin bisa melihat daftar IP yang diblokir dan melakukan unblock manual.
     */
    public function up(): void
    {
        Schema::create('blocked_clients', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->index(); // IPv4 dan IPv6
            $table->text('user_agent')->nullable(); // Info browser penyerang
            $table->unsignedInteger('attempt_count')->default(0); // Hitungan gagal sebelum blokir
            $table->boolean('is_blocked')->default(false); // Status aktif blokir
            $table->timestamp('blocked_until')->nullable(); // Kapan blokir otomatis dibuka
            $table->string('reason')->nullable(); // Alasan blokir: "Brute Force Login", "SQL Injection Detected", dll
            $table->string('blocked_route')->nullable(); // Route yang diblokir (login, api, dll)
            $table->timestamps();
            
            // Index untuk query cepat
            $table->index(['ip_address', 'is_blocked']);
            $table->index('blocked_until');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocked_clients');
    }
};
