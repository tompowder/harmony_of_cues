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
    Schema::create('shop_infos', function (Blueprint $table) {
      $table->id();
      $table->foreignId('shop_point_id')->constrained('shop_points');
      $table->foreignId('info_id')->constrained('infos');
      $table->unsignedInteger('price')->nullable();
      $table->timestamps();

      $table->unique([
        'shop_point_id',
        'info_id',
      ]);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('shop_infos');
  }
};
