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
    Schema::create('infos', function (Blueprint $table) {
      $table->id();

      $table->enum('info_type', [
        'monster',
        'boss',
        'synergy',
        'other',
      ]);

      $table->enum('info_condition', [
        'wild',
        'pristine',
      ]);

      $table->string('info_title');
      $table->text('info_text');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('infos');
  }
};
