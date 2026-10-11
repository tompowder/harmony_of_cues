<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AbilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('abilities')->insert(
        [
            [
                'id' => 1,
                'name' => 'Burning Body',
                'description' => 'Attackers receive damage when physically attacking the target',
                'effect' => '{ "trigger": "taking_damage", "condition": {"move_type": "attack"}, "effect": {"use_move": {"target": "attacker", "move_id": 12 }}}',
            ],
            [
                'id' => 2,
                'name' => 'Self Necromancy',
                'description' => 'When the user is defeated it will continue to attack until all of the users ally are defeated',
                'effect' => '{ "trigger": "defeated_skip", "effect": {"use_move": {"target": "random_enemy", "move_id": 13 }}}',
            ],
            [
                'id' => 3,
                'name' => 'Pack Hunter',
                'description' => 'Become stronger when another ally of the same type is alive.',
                'effect' => '{ "trigger": "encounter_start", "condition": {"same_type_allies_alive": true}, "update_trigger": "ally_defeated", "effect": { "stat_change": {"attack_multiplier": 1.30, "target": "self"}}}',
            ],
            [
                'id' => 4,
                'name' => 'Dual Wielding',
                'description' => 'Allows the user to use 2 weapons.',
                'effect' => '{ "trigger": "equip_weapon", "effect": {"weapon_amount": 2}}',
            ],
            [
                'id' => 5,
                'name' => 'Layered Armor',
                'description' => 'Allows the user to use 2 armors.',
                'effect' => '{ "trigger": "equip_armor", "effect": {"armor_amount": 2}}',
            ],
            [
                'id' => 6,
                'name' => 'Fire Guard',
                'description' => 'Reduce the amount of Fire damage taken.',
                'effect' => '{ "trigger": "taking_damage", "effect": {"damage_multiplier": { "affinity": "Fire", "value": 0.75}}',
            ],
            [
                'id' => 7,
                'name' => 'Burning Resolve',
                'description' => 'The user becomes stronger when badly injured.',
                'effect' => '{ "trigger": "hp_changed", "condition": {"hp_percent": 40}, "effect": { "stat_change": {"attack_multiplier": 1.30, "target": "self"}, "enable_synergy": 1} "duration": "battle"}',
            ],
            [
                'id' => 8,
                'name' => 'Coward',
                'description' => 'Will run away after 5 turns.',
                'effect' => '{ "trigger": "turn_end", "condition": {"turn": 5} "effect": {"flee": true}}',
            ]
        ]);
    }
}
