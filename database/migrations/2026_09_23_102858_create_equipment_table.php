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
    Schema::create('equipment', function (Blueprint $table) {
      $table->id();
      $table->foreignId('affinity_id')->nullable()->constrained('affinities');
      $table->foreignId('ability_id')->nullable()->constrained('abilities');
      $table->foreignId('move_id')->nullable()->constrained('moves');

      $table->enum('equipment_type', [
        'weapon',
        'armor',
      ]);

      $table->string('name');
      $table->unsignedInteger('boost_hp');
      $table->unsignedInteger('boost_mp');
      $table->unsignedInteger('boost_attack');
      $table->unsignedInteger('boost_magic');
      $table->unsignedInteger('boost_defense');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('equipment');
  }
};
