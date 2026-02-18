<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add user tracking columns to blocked_clients table.
     * This allows admin to monitor which users logged in from which IPs.
     */
    public function up(): void
    {
        Schema::table('blocked_clients', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            $table->string('user_name')->nullable()->after('user_id');
            $table->timestamp('last_login_at')->nullable()->after('blocked_route');
            $table->unsignedInteger('login_count')->default(0)->after('last_login_at');
            
            // Index for quick user lookups
            $table->index(['user_id', 'ip_address']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blocked_clients', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex(['user_id', 'ip_address']);
            $table->dropColumn(['user_id', 'user_name', 'last_login_at', 'login_count']);
        });
    }
};
