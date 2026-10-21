<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('entities')->insert(
        [
            [
                'id' => 1,
                'level_schema_id' => 1, // attack hero
                'affinity_id' => 4, // none
                'ability_id' => 4, // dual wielding
                'name' => 'jonathan',
            ],
            [
                'id' => 2,
                'level_schema_id' => 2, // magic hero
                'affinity_id' => 4, // none
                'ability_id' => 5, // layered armor
                'name' => 'rosa',
            ],
            [
                'id' => 3,
                'level_schema_id' => 3, // goblin
                'affinity_id' => 4, // none
                'ability_id' => null,
                'name' => 'goblin',
            ],
            [
                'id' => 4,
                'level_schema_id' => 4, // golden goblin
                'affinity_id' => 5, // metal
                'ability_id' => 8, // coward
                'name' => 'golden goblin',
            ],
            [
                'id' => 5,
                'level_schema_id' => 5, // slime
                'affinity_id' => 4, // none
                'ability_id' => null,
                'name' => 'slime',
            ],
            [
                'id' => 6,
                'level_schema_id' => 6, // metal slime
                'affinity_id' => 5, // metal
                'ability_id' => null,
                'name' => 'metal slime',
            ],
            [
                'id' => 7,
                'level_schema_id' => 7, // fire slime 
                'affinity_id' => 1, // fire
                'ability_id' => 1, // burning body
                'name' => 'fire slime',
            ],
            [
                'id' => 8,
                'level_schema_id' => 8, // forest wolf
                'affinity_id' => 3, // nature
                'ability_id' => 3, // pack hunter,
                'name' => 'forest wolf',
            ],
            [
                'id' => 9,
                'level_schema_id' => 9, // water djinn
                'affinity_id' => 2, // water
                'ability_id' => null,
                'name' => 'water djinn',
            ],
            [
                'id' => 10,
                'level_schema_id' => 10, // infernal knight
                'affinity_id' => 1, // fire
                'ability_id' => 6, // fire guard
                'name' => 'infernal knight',
            ],
            [
                 'id' => 11,
                 'level_schema_id' => 11, // dummy
                 'affinity_id' => 4, // none
                 'ability_id' => null, 
                 'name' => 'dummy',
            ]
        ]);

        $boss_ai = json_encode([
         'phases' => 
         [ 
            [
               'name' => 'phase_1',
               'condition' => [
                  'stat' => 'hp_percent',
                  'operator' => '>',
                  'value' => 75
               ],
               'moves' => [9, 10, 4, 16, 27], // Guard, Magic Guard, Flame Point, Blazing Kick, Pommel Strike
               'move_selection' => 'careful'
            ],
            [
               'name' => 'phase_2',
               'condition' => [
                  'stat' => 'hp_percent',
                  'operator' => '<=',
                  'value' => 75
               ],
               'moves' => [4, 16, 5, 14, 27], // Flame Point, Blazing Kick, Fire Burst, Flame Slash, Pommel Strike
               'move_selection' => 'random'
            ],
            [
               'name' => 'phase_3',
               'condition' => [
                  'stat' => 'hp_percent',
                  'operator' => '<=',
                  'value' => 40
               ],
               'moves' => [16, 5, 14, 25], // Blazing Kick, Fire Burst, Flame Slash => Draco Inferno, Flare Bash
               'move_selection' => 'reckless'
            ]
         ]
        ]);

        DB::table('model_monsters')->insert(
        [
            [
                'id' => 1,
                'entity_id' => 3, // goblin
                'gold_drop' => 5,
                'xp_drop' => 100,
                'boss' => false,
                'ai' => json_encode(['move_selection' => 'random'])
            ],
            [
                'id' => 2,
                'entity_id' => 4, // golden goblin
                'gold_drop' => 20,
                'xp_drop' => 500,
                'boss' => false,
                'ai' => json_encode(['move_selection' => 'random'])
            ],
            [
                'id' => 3,
                'entity_id' => 5, // slime
                'gold_drop' => 3,
                'xp_drop' => 50,
                'boss' => false,
                'ai' => json_encode(['move_selection' => 'random'])
            ],
            [
                'id' => 4,
                'entity_id' => 6, // metal slime
                'gold_drop' => 10,
                'xp_drop' => 750,
                'boss' => false,
                'ai' => json_encode(['move_selection' => 'careful'])
            ],
            [
                'id' => 5,
                'entity_id' => 7, // fire slime
                'gold_drop' => 8,
                'xp_drop' => 200,
                'boss' => false,
                'ai' => json_encode(['move_selection' => 'random'])
            ],
            [
                'id' => 6,
                'entity_id' => 8, // forest wolf
                'gold_drop' => 12,
                'xp_drop' => 300,
                'boss' => false,
                'ai' => json_encode(['move_selection' => 'random'])
            ],
            [
                'id' => 7,
                'entity_id' => 9, // water djinn
                'gold_drop' => 15,
                'xp_drop' => 400,
                'boss' => false,
                'ai' => json_encode(['move_selection' => 'random'])
            ],
            [
                'id' => 8,
                'entity_id' => 10, // infernal knight
                'gold_drop' => 50,
                'xp_drop' => 1000,
                'boss' => true,
                'ai' => $boss_ai
            ],
            [
                'id' => 9,
                'entity_id' => 11, // dummy
                'gold_drop' => 0,
                'xp_drop' => 0,
                'boss' => false,
                'ai' => json_encode(['move_selection' => 'skip'])
            ]
        ]);

        DB::table('equipment_monsters')->insert(
        [
            [
                'id' => 1,
                'model_monster_id' => 1, // goblin
                'equipment_id' => 8, // leather armor
                'drop_chance' => 10
            ],
            [
                'id' => 2,
                'model_monster_id' => 2, // golden goblin
                'equipment_id' => 7, // fire charm armor
                'drop_chance' => 45
            ],
            [
                'id' => 3,
                'model_monster_id' => 4, // metal slime
                'equipment_id' => 6, // steel armor
                'drop_chance' => 25
            ],
            [
                'id' => 4,
                'model_monster_id' => 7, // water djinn
                'equipment_id' => 10, // water robe
                'drop_chance' => 35
            ],
            [
                'id'  => 5,
                'model_monster_id' => 7, // water djinn
                'equipment_id' => 3, // water staff
                'drop_chance' => 15 
            ],
            [
                'id' => 6,
                'model_monster_id' => 8, // infernal knight
                'equipment_id' => 12, // ashen plate
                'drop_chance' => 100
            ],
            [
                'id' => 7,
                'model_monster_id' => 8, // infernal knight
                'equipment_id' => 2, // flame sword
                'drop_chance' => 100
            ]
        ]);
      
        DB::table('hero_stat_points')->insert(
        [
            [ // attack hero
                'id' => 1, 
                'stat_points_hp' => 0,
                'stat_points_mp' => 0,
                'stat_points_attack' => 5,
                'stat_points_magic' => 0,
                'stat_points_defense' => 2
            ],
            [ // magic hero
                'id' => 2, 
                'stat_points_hp' => 0,
                'stat_points_mp' => 0,
                'stat_points_attack' => 0,
                'stat_points_magic' => 5,
                'stat_points_defense' => 2
            ],
            [ // saved attack hero
                'id' => 3,
                'stat_points_hp' => 0,
                'stat_points_mp' => 0,
                'stat_points_attack' => 5,
                'stat_points_magic' => 0,
                'stat_points_defense' => 2
            ]
        ]);


        DB::table('model_heroes')->insert(
        [
            [
                'id' => 1,
                'entity_id' => 1, // jonathan the warrior
                'hero_stat_point_id' => 1, // attack hero
                'level' => 1,
            ],
            [
                'id' => 2,
                'entity_id' => 2, // rosa the mage
                'hero_stat_point_id' => 2, // magic hero
                'level' => 1,
            ]
        ]);
    }
}
