<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EncounterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('encounters')->insert(
        [
            [
                'id' => 1,
                'map_id' => 1, // alpha
                'encounter_chance' => 30
            ],
            [
                'id' => 2,
                'map_id' => 1, // alpha
                'encounter_chance' => 20
            ],
            [
                'id' => 3,
                'map_id' => 1, // alpha
                'encounter_chance' => 10
            ],
            [
                'id' => 4,
                'map_id' => 1, // alpha
                'encounter_chance' => 50
            ],
            [
                'id' => 5,
                'map_id' => null, // boss encounter
                'encounter_chance' => 100
            ],
            [
                'id' => 6,
                'map_id' => 2, // beta
                'encounter_chance' => 50
            ]
        ]);
 
        DB::table('encounter_monsters')->insert(
        [
            [
                'id' => 1,
                'encounter_id' => 1,
                'model_monster_id' => 1, // goblin
                'min_level' => 1,
                'max_level' => 3,
                'quantity' => 1
            ],
            [
                'id' => 2,
                'encounter_id' => 2,
                'model_monster_id' => 3, // slime
                'min_level' => 2,
                'max_level' => 4,
                'quantity' => 2
            ],
            [
                'id' => 3,
                'encounter_id' => 3,
                'model_monster_id' => 2, // golden goblin
                'min_level' => 2,
                'max_level' => 5,
                'quantity' => 1
            ],
            [
                'id' => 4,
                'encounter_id' => 4,
                'model_monster_id' => 6, // forest wolf
                'min_level' => 1,
                'max_level' => 2,
                'quantity' => 2
            ],
            [
                'id' => 5,
                'encounter_id' => 5,
                'model_monster_id' => 8, // infernal knight
                'min_level' => 5,
                'max_level' => 5,
                'quantity' => 1
            ]
        ]);
        
    }
}
