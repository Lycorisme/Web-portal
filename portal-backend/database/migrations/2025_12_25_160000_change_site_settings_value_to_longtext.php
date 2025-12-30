<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Change the 'value' column from TEXT to LONGTEXT to accommodate
     * base64-encoded images which can be very large.
     */
    public function up(): void
    {
        // Use raw SQL to alter the column type to LONGTEXT
        DB::statement('ALTER TABLE site_settings MODIFY value LONGTEXT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to TEXT (note: this may truncate data if values are too long)
        DB::statement('ALTER TABLE site_settings MODIFY value TEXT NULL');
    }
};
