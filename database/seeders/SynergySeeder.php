<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SynergySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('synergies')->insert(
        [
            [
                'id' => 1,
                'move_id' => 20, // Draco Inferno
                'calculation' => '{"stat": "attack", "method": "highest"}'
            ],
            [
                'id' => 2,
                'move_id' => 20, // Holy Water
                'calculation' => '{"stat": "magic", "method": "highest"}'
            ],
            [
                'id' => 3,
                'move_id' => 22, // Burning Catastrophe
                'calculation' => '{"stat": "magic", "method": "average"}'
            ]
        ]);

        DB::table('synergy_moves')->insert(
        [
            [
                'id' => 1,
                'move_id' => 14, // Flame Slash
                'synergy_id' => 1, // Draco Inferno
                'participant' => 'self'
            ],
            [
                'id' => 2,
                'move_id' => 7, // Heal
                'synergy_id' => 2, // Holy Water
                'participant' => 'ally'
            ],
            [
                'id' => 3,
                'move_id' => 1, // Water blast
                'synergy_id' => 2, // Holy Water
                'participant' => 'ally'
            ],
            [
                'id' => 4,
                'move_id' => 2, // Nature's wrath
                'synergy_id' => 3, // Burning Catastrophe
                'participant' => 'ally'
            ],
            [
                'id' => 5,
                'move_id' => 4, // Flame Point
                'synergy_id' => 3, // Burning Catastrophe
                'participant' => 'ally'
            ]
        ]);
    }
}
