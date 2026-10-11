<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('equipment')->insert(
        [
            [
                'id' => 1,
                'affinity_id' => null, 
                'ability_id' => null,
                'move_id' => 13, // slashing
                'type' => 'weapon',
                'name' => 'Iron Sword',
                'boost_hp' => 0,
                'boost_mp' => 0,
                'boost_attack' => 50,
                'boost_magic' => 0,
                'boost_defense' => 0,
            ],
            [
                'id' => 2,
                'affinity_id' => null, 
                'ability_id' => null,
                'move_id' => 14, // flame slash
                'type' => 'weapon',
                'name' => 'Flame Sword',
                'boost_hp' => 0,
                'boost_mp' => 0,
                'boost_attack' => 75,
                'boost_magic' => 0,
                'boost_defense' => 0,
            ],
            [
                'id' => 3,
                'affinity_id' => null, 
                'ability_id' => null,
                'move_id' => 1, // water blast
                'type' => 'weapon',
                'name' => 'Water Staff',
                'boost_hp' => 0,
                'boost_mp' => 20,
                'boost_attack' => 0,
                'boost_magic' => 75,
                'boost_defense' => 0,
            ],
            [
                'id' => 4,
                'affinity_id' => null, 
                'ability_id' => null,
                'move_id' => null, 
                'type' => 'weapon',
                'name' => 'Arcane Staff',
                'boost_hp' => 0,
                'boost_mp' => 20,
                'boost_attack' => 0,
                'boost_magic' => 50,
                'boost_defense' => 0,
            ],
            [
                'id' => 5,
                'affinity_id' => null, 
                'ability_id' => null,
                'move_id' => 2, // nature's wrath
                'type' => 'weapon',
                'name' => 'Nature Staff',
                'boost_hp' => 0,
                'boost_mp' => 20,
                'boost_attack' => 0,
                'boost_magic' => 50,
                'boost_defense' => 0,
            ],
            [
                'id' => 6,
                'affinity_id' => 5, // metal
                'ability_id' => null,
                'move_id' => null,
                'type' => 'armor',
                'name' => 'Steel Armor',
                'boost_hp' => 0,
                'boost_mp' => 0,
                'boost_attack' => 0,
                'boost_magic' => 0,
                'boost_defense' => 75,
            ],
            [
                'id' => 7,
                'affinity_id' => 1, // fire
                'ability_id' => 6, // fire guard
                'move_id' => null,
                'type' => 'armor',
                'name' => 'Fire Charm Armor',
                'boost_hp' => 0,
                'boost_mp' => 0,
                'boost_attack' => 15,
                'boost_magic' => 10,
                'boost_defense' => 60,
            ],
            [
                'id' => 8,
                'affinity_id' => 4, // none
                'ability_id' => null,
                'move_id' => null,
                'type' => 'armor',
                'name' => 'Leather Armor',
                'boost_hp' => 20,
                'boost_mp' => 0,
                'boost_attack' => 15,
                'boost_magic' => 0,
                'boost_defense' => 25,
            ],
            [
                'id' => 9,
                'affinity_id' => 4, // none
                'ability_id' => null,
                'move_id' => null,
                'type' => 'armor',
                'name' => 'Robe',
                'boost_hp' => 10,
                'boost_mp' => 20,
                'boost_attack' => 0,
                'boost_magic' => 30,
                'boost_defense' => 20,
            ],
            [
                'id' => 10,
                'affinity_id' => 2, // water
                'ability_id' => null,
                'move_id' => null,
                'type' => 'armor',
                'name' => 'Water Robe',
                'boost_hp' => 30,
                'boost_mp' => 20,
                'boost_attack' => 20,
                'boost_magic' => 25,
                'boost_defense' => 25,
            ],
            [
                'id' => 11,
                'affinity_id' => 1, // fire
                'ability_id' => null,
                'move_id' => null,
                'type' => 'armor',
                'name' => 'Fire Robe',
                'boost_hp' => 20,
                'boost_mp' => 20,
                'boost_attack' => 0,
                'boost_magic' => 20,
                'boost_defense' => 35,
            ],
            [
                'id' => 12,
                'affinity_id' => 1, // fire
                'ability_id' => 7, // burning resolve
                'move_id' => null,
                'type' => 'armor',
                'name' => 'Ashen Plate',
                'boost_hp' => 20,
                'boost_mp' => 20,
                'boost_attack' => 0,
                'boost_magic' => 20,
                'boost_defense' => 40,
            ]
        ]);
    }
}
