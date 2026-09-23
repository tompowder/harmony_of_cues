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
    Schema::create('transition_points', function (Blueprint $table) {
      $table->id();
      $table->foreignId('point_id')->unique()->constrained('points');
      $table->foreignId('target_map_id')->constrained('maps');
      $table->integer('target_x')->nullable();
      $table->integer('target_y')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('transition_points');
  }
};
