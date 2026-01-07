<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('article_tag', function (Blueprint $table) {
            $table->foreignId('article_id')
                  ->constrained('articles')
                  ->onDelete('cascade');
            $table->foreignId('tag_id')
                  ->constrained('tags')
                  ->onDelete('cascade');
            
            // Composite primary key
            $table->primary(['article_id', 'tag_id']);
            
            // Index for faster lookups
            $table->index('tag_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_tag');
    }
};
