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
    Schema::create('equipment_monsters', function (Blueprint $table) {
      $table->id();
      $table->foreignId('equipment_id')->constrained('equipment');
      $table->foreignId('entity_id')->constrained('entities');
      $table->unsignedTinyInteger('drop_chance')->nullable();
      $table->timestamps();

      $table->check('drop_chance IS NULL OR drop_chance BETWEEN 1 AND 100');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('equipment_monsters');
  }
};
