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
      Schema::create('affinity_relationships', function (Blueprint $table) {
         $table->id();
         $table->foreignId('affinity_id')->constrained('affinities');
         $table->foreignId('target_affinity_id')->constrained('affinities');
         $table->decimal('multiplier');
         $table->timestamps();

         $table->unique([
            'affinity_id',
            'target_affinity_id',
         ]);
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('affinity_relationships');
   }
};
