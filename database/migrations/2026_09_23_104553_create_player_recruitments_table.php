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
    Schema::create('player_recruitments', function (Blueprint $table) {
      $table->id();
      $table->foreignId('player_id')->constrained('players');
      $table->foreignId('recruitment_point_id')->constrained('recruitment_points');
      $table->timestamps();

      $table->unique([
        'player_id',
        'recruitment_point_id',
      ]);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('player_recruitments');
  }
};
