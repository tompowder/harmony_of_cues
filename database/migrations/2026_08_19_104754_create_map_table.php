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
      Schema::create('map', function (Blueprint $table) 
      {
        $table->id();
        $table->string("name");
        $table->text("map_data");
      });

      Schema::create('point', function (Blueprint $table) 
      {
        $table->id();
        $table->integer("map_id");
        $table->string("name");
        $table->integer("x");
        $table->integer("y");
      });

      
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::dropIfExists('map');
      Schema::dropIfExists('point');
    }
};
