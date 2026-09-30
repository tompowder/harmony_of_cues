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
      Schema::create('model_monsters', function (Blueprint $table) {
         $table->id();
         $table->foreignId('entity_id')->unique()->constrained('entities');
         $table->unsignedInteger('gold_drop');
         $table->unsignedInteger('xp_drop');
         $table->boolean('boss');
         $table->json('ai');
         $table->timestamps();
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('model_monsters');
   }
};
