<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      DB::table('players')->insert([
         'id' => 1,
         'map_id' => 1, // alpha
         'user_id' => 1, // test
         'gold' => 20,
         'x' => 17,
         'y' => 8,
      ]);
    }
}
