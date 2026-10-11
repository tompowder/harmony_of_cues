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
                'name' => 'boss', // boss
                'x' => 12,
                'y' => 2,
            ],
            [
                'id' => 11,
                'map_id' => 2, // beta
                'name' => 'transition', // transition
                'x' => 4,
                'y' => 0,
            ],
            [
                'id' => 12,
                'map_id' => 3, // gamma
                'name' => 'save', // save
                'x' => 30,
                'y' => 0,
            ],
            [
                'id' => 13,
                'map_id' => 3, // gamma
                'name' => 'shop', // shop
                'x' => 8,
                'y' => 5,
            ],
            [
                'id' => 14,
                'map_id' => 3, // gamma
                'name' => 'recruitment', // recruitment
                'x' => 24,
                'y' => 3,
            ],
            [
                'id' => 14,
                'map_id' => 3, // gamma
                'name' => 'transition 1', // transition
                'x' => 12,
                'y' => 7,
            ],
            [
                'id' => 14,
                'map_id' => 3, // gamma
                'name' => 'transition 2', // transition
                'x' => 4,
                'y' => 8,
            ],
        ]);

        // DB::table('info_points')->insert(
        // [
        //     [
                
        //     ]
        // ]);
    }
}
