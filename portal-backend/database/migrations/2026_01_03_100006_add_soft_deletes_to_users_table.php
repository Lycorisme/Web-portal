<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Menambahkan soft delete ke tabel users.
     * Berguna untuk keamanan - admin yang dihapus bisa di-restore.
     * Juga menjaga referential integrity dengan tabel lain.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Soft Delete column
            $table->softDeletes()->after('updated_at');
            
            // Additional security fields
            $table->timestamp('last_login_at')->nullable()->after('remember_token');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            $table->unsignedInteger('failed_login_count')->default(0)->after('last_login_ip');
            $table->timestamp('locked_until')->nullable()->after('failed_login_count');
            
            // Index
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'deleted_at',
                'last_login_at', 
                'last_login_ip',
                'failed_login_count',
                'locked_until'
            ]);
        });
    }
};
