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
      Schema::create('move_saved_heroes', function (Blueprint $table) {
         $table->id();
         $table->foreignId('move_id')->nullable()->constrained('moves');
         $table->foreignId('saved_heroes_id')->nullable()->constrained('saved_heroes');
         $table->unsignedInteger('slot');
         
         $table->enum('source', [
            'level',
            'shop',
            'weapon',
         ]);
         $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::dropIfExists('move_saved_heroes');
    }
};
