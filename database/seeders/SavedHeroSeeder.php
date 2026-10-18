<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SavedHeroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      DB::table('saved_heroes')->insert(
      [
        'id' => 1,
        'player_id' => 1,
        'hero_stat_point_id' => 3,
        'model_hero_id' => 1,
        'level' => 1,
        'xp' => 0
      ]);

      DB::table('move_saved_heroes')->insert(
      [
        [
          'id' => 1,
          'move_id' => 15,
          'saved_heroes_id' => 1,
          'slot' => 1,
          'source' => 'level'
        ],
        [
          'id' => 2,
          'move_id' => 18,
          'saved_heroes_id' => 1,
          'slot' => 1,
          'source' => 'level'
        ]
      ]);
      
    }
}
