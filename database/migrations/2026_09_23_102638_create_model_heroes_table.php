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
    Schema::create('model_heroes', function (Blueprint $table) {
      $table->id();
      $table->foreignId('entity_id')->unique()->constrained('entities');
      $table->foreignId('hero_stat_point_id')->unique()->constrained('hero_stat_points');
      $table->unsignedInteger('level');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('model_heroes');
  }
};
