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
    Schema::create('info_encounters', function (Blueprint $table) {
      $table->id();
      $table->foreignId('map_id')->constrained('maps');
      $table->unsignedTinyInteger('encounter_chance');
      $table->timestamps();

      $table->check('encounter_chance BETWEEN 1 AND 100');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('info_encounters');
  }
};
