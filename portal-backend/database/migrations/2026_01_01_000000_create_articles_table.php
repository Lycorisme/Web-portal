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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('category')->default('Umum');
            $table->integer('read_time')->default(5); // in minutes
            $table->enum('status', ['draft', 'pending', 'published', 'rejected'])->default('draft');
            
            // Security scan fields
            $table->enum('security_status', ['pending', 'passed', 'warning', 'danger'])->default('pending');
            $table->string('security_message')->nullable();
            $table->string('security_detail')->nullable();
            
            // Author relationship
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            
            // SEO fields
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            
            // Stats
            $table->unsignedBigInteger('views')->default(0);
            
            // Timestamps
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('status');
            $table->index('category');
            $table->index('author_id');
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
