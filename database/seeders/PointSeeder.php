<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('points')->insert(
        [
            [
                'id' => 1,
                'map_id' => 1, // alpha
                'name' => 'save', // save
                'x' => 1,
                'y' => 1,
            ],
            [
                'id' => 2,
                'map_id' => 1, // alpha
                'name' => 'shop', // shop
                'x' => 20,
                'y' => 1,
            ],
            [
                'id' => 3,
                'map_id' => 1, // alpha
                'name' => 'info', // info
                'x' => 1,
                'y' => 8,
            ],
            [
                'id' => 4,
                'map_id' => 1, // alpha
                'name' => 'recruitment', // recruitment
                'x' => 11,
                'y' => 8,
            ],
            [
                'id' => 5,
                'map_id' => 1, // alpha
                'name' => 'boss', // boss
                'x' => 6,
                'y' => 4,
            ],
            [
                'id' => 6,
                'map_id' => 1, // alpha
                'name' => 'transition 1', // transition
                'x' => 5,
                'y' => 4,
            ],
            [
                'id' => 7,
                'map_id' => 1, // alpha
                'name' => 'transition 2', // transition
                'x' => 12,
                'y' => 9,
            ],
            [
                'id' => 8,
                'map_id' => 2, // beta
                'name' => 'save', // save
                'x' => 4,
                'y' => 8,
            ],
            [
                'id' => 9,
                'map_id' => 2, // beta
                'name' => 'shop', // shop
                'x' => 0,
                'y' => 7,
            ],
            [
                'id' => 10,
                'map_id' => 2, // beta
                'name' => 'info', // info
                'x' => 1,
                'y' => 3,
            ],
            [
                'id' => 11,
                'map_id' => 2, // beta
                'name' => 'boss', // boss
                'x' => 12,
                'y' => 2,
            ],
            [
                'id' => 12,
                'map_id' => 2, // beta
                'name' => 'transition', // transition
                'x' => 4,
                'y' => 0,
            ],
            [
                'id' => 13,
                'map_id' => 3, // gamma
                'name' => 'save', // save
                'x' => 30,
                'y' => 0,
            ],
            [
                'id' => 14,
                'map_id' => 3, // gamma
                'name' => 'shop', // shop
                'x' => 8,
                'y' => 5,
            ],
            [
                'id' => 15,
                'map_id' => 3, // gamma
                'name' => 'info 1', // info
                'x' => 0,
                'y' => 4,
            ],
            [
                'id' => 16,
                'map_id' => 3, // gamma
                'name' => 'info 2', // info
                'x' => 24,
                'y' => 4,
            ],
            [
                'id' => 17,
                'map_id' => 3, // gamma
                'name' => 'recruitment', // recruitment
                'x' => 24,
                'y' => 3,
            ],
            [
                'id' => 18,
                'map_id' => 3, // gamma
                'name' => 'transition 1', // transition
                'x' => 12,
                'y' => 7,
            ],
            [
                'id' => 19,
                'map_id' => 3, // gamma
                'name' => 'transition 2', // transition
                'x' => 4,
                'y' => 8,
            ],
        ]);

         DB::table('info_points')->insert(
         [
            [
               'id' => 1,
               'point_id' => 3, // alpha 1, 8
               'info_id' => 21 // Golden Goblin wild
            ],
            [
               'id' => 2,
               'point_id' => 10, // beta 1, 3
               'info_id' => 22 // Metal Slime wild
            ],
            [
               'id' => 3,
               'point_id' => 15, // gamma 0, 4
               'info_id' => 23 // water djinn wild
            ],
            [
               'id' => 4,
               'point_id' => 16, // gamma 24, 8
               'info_id' => 24 // infernal knight wild
            ]
         ]);

         DB::table('boss_points')->insert(
         [
            [
               'id' => 1,
               'point_id' => 11, // beta 12, 2
               'encounter_id' => 5 // infernal knight
            ]
         ]);

         DB::table('transition_points')->insert(
         [
            [
               'id' => 1,
               'point_id' => 6, // alpha 5, 4
               'target_map_id' => 3, // gamma
               'target_x' => 4,
               'target_y' => 7
            ],
            [
               'id' => 2,
               'point_id' => 7, // alpha 12, 9
               'target_map_id' => 2, // beta
               'target_x' => 4,
               'target_y' => 1
            ],
            [
               'id' => 3,
               'point_id' => 12, // beta 12, 7
               'target_map_id' => 1, // alpha
               'target_x' => 12,
               'target_y' => 8
            ],
            [
               'id' => 4,
               'point_id' => 18, // gamma 4, 0
               'target_map_id' => 1, // will go to a niew map later
               'target_x' => 6,
               'target_y' => 4
            ],
            [
               'id' => 5,
               'point_id' => 19, // gamma 4, 0
               'target_map_id' => 1, // alpha
               'target_x' => 6,
               'target_y' => 4
            ]
         ]);

         DB::table('recruitment_points')->insert(
         [
            [
               'id' => 1,
               'point_id' => 4, // alpha 11, 8
               'model_hero_id' => 2,
               'condition' => json_encode(['conditions' => ['party_level' => 5]])
            ],
            [
               'id' => 2,
               'point_id' => 17, // gamma 24, 3
               'model_hero_id' => 2,
               'condition' => json_encode(['conditions' => ['defeat_monster' => 8]])
            ]
         ]);

         DB::table('shop_points')->insert(
         [
            [
               'id' => 1,
               'point_id' => 2 // alpha 20, 1
            ],
            [
               'id' => 2,
               'point_id' => 9 // beta 0, 7
            ],
            [
               'id' => 3,
               'point_id' => 14 // gamma 8, 5
            ]
         ]);

         DB::table('shop_equipment')->insert(
         [
            [
               'id' => 1,
               'shop_point_id' => 1, // alpha
               'equipment_id' => 2,
               'price' => 10
            ]
         ]);

         DB::table('shop_infos')->insert(
         [
            [
               'id' => 1,
               'shop_point_id' => 1, // alpha
               'info_id' => 2,
               'price' => 10
            ]
         ]);

         DB::table('shop_moves')->insert(
         [
            [
               'id' => 1,
               'shop_point_id' => 1, // alpha
               'move_id' => 2,
               'price' => 10
            ]
         ]);
   }
}
