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
    Schema::create('level_schema_moves', function (Blueprint $table) {
      $table->id();
      $table->foreignId('level_schema_id')->constrained('level_schemas');
      $table->foreignId('move_id')->constrained('moves');
      $table->unsignedInteger('level_requirement');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('level_schema_moves');
  }
};
