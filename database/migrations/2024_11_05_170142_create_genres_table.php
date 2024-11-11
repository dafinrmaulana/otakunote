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
    Schema::create('genres', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('slug');
      $table->text('description')->nullable();
      $table->boolean('is_pinned')->default(false);
      $table->string('color')->nullable();
      $table->timestamps();

      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->unique(['name', 'user_id']);
      $table->unique(['slug', 'user_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('genres');
  }
};
