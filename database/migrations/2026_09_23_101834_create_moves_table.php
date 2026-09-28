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
    Schema::create('moves', function (Blueprint $table) {
      $table->id();
      $table->foreignId('affinity_id')->constrained('affinities');
      $table->string('name');

      $table->enum('type', [
        'attack',
        'magic',
      ]);

      $table->decimal('power');
      $table->json('effect');
      $table->string('description')->nullable();
      $table->unsignedInteger('cost');

      $table->enum('target', [
        'self',
        'ally',
        'allies',
        'self_or_ally',
        'self_and_ally',
        'self_or_ally_or_enemy',
        'enemy',
        'enemies',
        'all_except_self',
        'all',
      ]);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('moves');
  }
};
