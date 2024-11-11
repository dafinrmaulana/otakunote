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
    Schema::create('series', function (Blueprint $table) {
      $table->id();
      $table->string('title');
      $table->string('slug');
      $table->float('rating')->default(0.0);
      $table->enum('media', ['video', 'book'])->default('video');
      $table->string('thumbnail')->nullable();
      $table->string('author')->nullable();
      $table->string('studio')->nullable();
      $table->string('source_url')->nullable()->unique();
      $table->boolean('is_pinned')->default(false);
      $table->timestamps();

      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->foreignId('category_id')->constrained();
      $table->foreignId('status_id')->constrained();

      $table->unique(['title', 'user_id']);
      $table->unique(['slug', 'user_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('series');
  }
};
