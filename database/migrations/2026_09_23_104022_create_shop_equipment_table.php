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
      Schema::create('shop_equipment', function (Blueprint $table) {
         $table->id();
         $table->foreignId('shop_point_id')->constrained('shop_points');
         $table->foreignId('equipment_id')->constrained('equipment');
         $table->unsignedInteger('price');
         $table->timestamps();

         $table->unique([
            'shop_point_id',
            'equipment_id',
         ]);
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('shop_equipment');
   }
};
