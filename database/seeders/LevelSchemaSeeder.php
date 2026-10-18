<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelSchemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('level_schemas')->insert(
        [
            // attack hero
            [
                'id' => 1,
                'max_hp' => 800,
                'max_mp' => 100,
                'max_attack' => 300,
                'max_magic' => 100,
                'max_defense' => 200,
                'max_xp' => 10000
            ],
            // magic hero
            [
                'id' => 2,
                'max_hp' => 600,
                'max_mp' => 300,
                'max_attack' => 100,
                'max_magic' => 320,
                'max_defense' => 150,
                'max_xp' => 10000
            ],
            // goblin
            [
                'id' => 3,
                'max_hp' => 400,
                'max_mp' => 100,
                'max_attack' => 150,
                'max_magic' => 75,
                'max_defense' => 100,
                'max_xp' => 6000
            ],
            // golden goblin
            [
                'id' => 4,
                'max_hp' => 200,
                'max_mp' => 0,
                'max_attack' => 400,
                'max_magic' => 0,
                'max_defense' => 450,
                'max_xp' => 15000
            ],
            // slime
            [
                'id' => 5,
                'max_hp' => 300,
                'max_mp' => 0,
                'max_attack' => 100,
                'max_magic' => 0,
                'max_defense' => 50,
                'max_xp' => 4000
            ],
            // metal slime
            [
                'id' => 6,
                'max_hp' => 200,
                'max_mp' => 100,
                'max_attack' => 150,
                'max_magic' => 0,
                'max_defense' => 200,
                'max_xp' => 16000
            ],
            // fire slime 
            [
                'id' => 7,
                'max_hp' => 500,
                'max_mp' => 0,
                'max_attack' => 300,
                'max_magic' => 0,
                'max_defense' => 50,
                'max_xp' => 8000
            ],
            // forest wolf
            [
                'id' => 8,
                'max_hp' => 500,
                'max_mp' => 0,
                'max_attack' => 250,
                'max_magic' => 0,
                'max_defense' => 150,
                'max_xp' => 12000
            ],
            // water djinn
            [
                'id' => 9,
                'max_hp' => 600,
                'max_mp' => 300,
                'max_attack' => 200,
                'max_magic' => 400,
                'max_defense' => 200,
                'max_xp' => 12000
            ],
            // infernal knight
            [
                'id' => 10,
                'max_hp' => 1000,
                'max_mp' => 400,
                'max_attack' => 400,
                'max_magic' => 400,
                'max_defense' => 300,
                'max_xp' => 25000
            ],
            // dummy
            [
                 'id' => 11,
                 'max_hp' => 10000,
                 'max_mp' => 0,
                 'max_attack' => 0,
                 'max_magic' => 0,
                 'max_defense' => 0,
                 'max_xp' => 0
            ]
        ]);

        DB::table('level_schema_moves')->insert(
        [
            [
                'id' => 1,
                'level_schema_id' => 1, // attack hero
                'move_id' => 15, // Tackle
                'level_requirement' => 1
            ],
            [
                'id' => 2,
                'level_schema_id' => 1, // attack hero
                'move_id' => 18, // Barbed Upercut
                'level_requirement' => 1
            ],
            [
                'id' => 3,
                'level_schema_id' => 1, // attack hero
                'move_id' => 16, // Blazing Kick
                'level_requirement' => 5
            ],
            [
                'id' => 4,
                'level_schema_id' => 1, // attack hero
                'move_id' => 1, // water blast
                'level_requirement' => 7
            ],
            [
                'id' => 5,
                'level_schema_id' => 1, // attack hero
                'move_id' => 19, // Metal Clad Charge 
                'level_requirement' => 10
            ],
            [
                'id' => 6,
                'level_schema_id' => 2, // magic hero
                'move_id' => 3, // Arcane Bolt
                'level_requirement' => 1
            ],
            [
                'id' => 7,
                'level_schema_id' => 2, // magic hero
                'move_id' => 7, // Heal
                'level_requirement' => 1
            ],
            [
                'id' => 8,
                'level_schema_id' => 2, // magic hero
                'move_id' => 6, // Root Hook
                'level_requirement' => 3
            ],
            [
                'id' => 9,
                'level_schema_id' => 2, // magic hero
                'move_id' => 17, // Water Whip
                'level_requirement' => 6
            ],
            [
                'id' => 10,
                'level_schema_id' => 2, // magic hero
                'move_id' => 4, // Flame Point
                'level_requirement' => 8
            ],
            [
                'id' => 11,
                'level_schema_id' => 2, // magic hero
                'move_id' => 2, // Nature's wrath
                'level_requirement' => 11
            ],
            [
                'id' => 12,
                'level_schema_id' => 3, // goblin
                'move_id' => 23, // bite
                'level_requirement' => 1
            ],
            [
                'id' => 13,
                'level_schema_id' => 3, // goblin
                'move_id' => 6, // Root Hook
                'level_requirement' => 1
            ],
            [
                'id' => 14,
                'level_schema_id' => 4, // golden goblin
                'move_id' => 23, // bite
                'level_requirement' => 1 
            ],
            [
                'id' => 15,
                'level_schema_id' => 4, // golden goblin
                'move_id' => 26, // metal claw
                'level_requirement' => 1 
            ],
            [
                'id' => 16,
                'level_schema_id' => 4, // golden goblin
                'move_id' => 9, // Guard
                'level_requirement' => 1
            ],
            [
                'id' => 17,
                'level_schema_id' => 4, // golden goblin
                'move_id' => 19, // Metal Clad Charge
                'level_requirement' => 5
            ],
            [
                'id' => 18,
                'level_schema_id' => 5, // slime
                'move_id' => 15, // Tackle
                'level_requirement' => 1
            ],
            [
                'id' => 19,
                'level_schema_id' => 5, // slime
                'move_id' => 24, // nature scratch
                'level_requirement' => 1
            ],
            [
                'id' => 20,
                'level_schema_id' => 6, // metal slime
                'move_id' => 15, // tackle
                'level_requirement' => 1
            ],
            [
                'id' => 21,
                'level_schema_id' => 6, // metal slime
                'move_id' => 9, // guard
                'level_requirement' => 1
            ],
            [
                'id' => 22,
                'level_schema_id' => 6, // metal slime
                'move_id' => 10, // magic guard
                'level_requirement' => 1
            ],
            [
                'id' => 23,
                'level_schema_id' => 7, // fire slime
                'move_id' => 15, // Tackle
                'level_requirement' => 1
            ],
            [
                'id' => 24,
                'level_schema_id' => 7, // fire slime
                'move_id' => 25, // fire bash
                'level_requirement' => 1
            ],
            [
                'id' => 25,
                'level_schema_id' => 8, // forest wolf
                'move_id' => 15, // Tackle
                'level_requirement' => 1
            ],
            [
                'id' => 26,
                'level_schema_id' => 8, // forest wolf
                'move_id' => 24, // nature scratch
                'level_requirement' => 1   
            ],
            [
                'id' => 27,
                'level_schema_id' => 8, // forest wolf
                'move_id' => 23, // bite
                'level_requirement' => 1   
            ],
            [
                'id' => 28,
                'level_schema_id' => 9, // water djinn
                'move_id' => 1, // water blast
                'level_requirement' => 1   
            ],
            [
                'id' => 29,
                'level_schema_id' => 9, // water djinn
                'move_id' => 17, // water whip
                'level_requirement' => 1   
            ],
            [
                'id' => 30,
                'level_schema_id' => 9, // water djinn
                'move_id' => 7, // heal
                'level_requirement' => 1   
            ],
            [
                'id' => 31,
                'level_schema_id' => 9, // water djinn
                'move_id' => 2, // nature's wrath
                'level_requirement' => 1   
            ],
            [
                'id' => 32,
                'level_schema_id' => 10, // infernal knight
                'move_id' => 4, // flame point
                'level_requirement' => 1   
            ],
            [
                'id' => 33,
                'level_schema_id' => 10, // infernal knight
                'move_id' => 16, // blazing kick
                'level_requirement' => 1   
            ],
            [
                'id' => 34,
                'level_schema_id' => 10, // infernal knight
                'move_id' => 27, // pommel strike
                'level_requirement' => 1
            ],
            [
                'id' => 35,
                'level_schema_id' => 10, // infernal knight
                'move_id' => 9, // guard
                'level_requirement' => 1
            ],
            [
                'id' => 36,
                'level_schema_id' => 10, // infernal knight
                'move_id' => 5, // fire burst
                'level_requirement' => 1
            ]
        ]);
    }
}
