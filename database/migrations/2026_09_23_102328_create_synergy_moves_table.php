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
    Schema::create('synergy_moves', function (Blueprint $table) {
      $table->id();
      $table->foreignId('move_id')->constrained('moves');
      $table->foreignId('synergy_id')->constrained('synergies');

      $table->enum('participant', [
        'self',
        'ally',
        'enemy',
        'ally_or_enemy',
      ]);

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('synergy_moves');
  }
};
