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
    Schema::create('encounter_monsters', function (Blueprint $table) {
      $table->id();
      $table->foreignId('encounter_id')->constrained('encounters');
      $table->foreignId('model_monster_id')->constrained('model_monsters');
      $table->unsignedInteger('min_level');
      $table->unsignedInteger('max_level');
      $table->unsignedInteger('quantity');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('encounter_monsters');
  }
};
