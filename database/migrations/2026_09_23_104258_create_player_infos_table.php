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
    Schema::create('player_infos', function (Blueprint $table) {
      $table->id();
      $table->foreignId('player_id')->constrained('players');
      $table->foreignId('info_id')->constrained('infos');
      $table->timestamps();

      $table->unique([
        'player_id',
        'info_id',
      ]);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('player_infos');
  }
};
