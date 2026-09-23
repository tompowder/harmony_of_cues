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
    Schema::create('hero_stat_points', function (Blueprint $table) {
      $table->id();
      $table->unsignedInteger('stat_points_hp');
      $table->unsignedInteger('stat_points_mp');
      $table->unsignedInteger('stat_points_attack');
      $table->unsignedInteger('stat_points_magic');
      $table->unsignedInteger('stat_points_defense');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('hero_stat_points');
  }
};
