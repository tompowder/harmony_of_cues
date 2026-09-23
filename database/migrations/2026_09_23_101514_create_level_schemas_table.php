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
    Schema::create('level_schemas', function (Blueprint $table) {
      $table->id();
      $table->unsignedInteger('max_hp');
      $table->unsignedInteger('max_mp');
      $table->unsignedInteger('max_attack');
      $table->unsignedInteger('max_magic');
      $table->unsignedInteger('max_defense');
      $table->unsignedInteger('max_xp');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('level_schemas');
  }
};
