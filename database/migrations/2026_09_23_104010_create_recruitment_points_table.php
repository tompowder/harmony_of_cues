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
    Schema::create('recruitment_points', function (Blueprint $table) {
      $table->id();
      $table->foreignId('point_id')->unique()->constrained('points');
      $table->foreignId('model_hero_id')->constrained('model_heroes');
      $table->json('condition');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('recruitment_points');
  }
};
