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
            'effect' => json_encode(['trigger' => 'taking_damage', 'condition' => ['move_type' => 'attack'], 'effect' => ['type' => 'use_move', 'target' => 'event.attacker', 'move_id' => 12]])
         ],
         [
            'id' => 2,
            'name' => 'Self Necromancy',
            'description' => 'When the user is defeated it will continue to attack until all of the users ally are defeated',
            'effect' => json_encode(['trigger' => 'defeated_skip', 'effect' => ['type' => 'use_move', 'target' => 'random_enemy', 'move_id' => 13]]),
         ],
         [
            'id' => 3,
            'name' => 'Pack Hunter',
            'description' => 'Become stronger when another ally of the same type is alive.',
            'effect' => json_encode(['trigger' => 'encounter_start', "condition" => ['same_type_allies_alive' => true], 'update_trigger' => 'ally_defeated', 'effect' => ['type' => 'stat_change', 'operator' => '*', 'stat' => 'attack', 'value' => 1.3, 'target' => 'self']])
         ],
         [
            'id' => 4,
            'name' => 'Dual Wielding',
            'description' => 'Allows the user to use 2 weapons.',
            'effect' => json_encode(['trigger' => 'equipment_check', 'effect' => ['type' => 'equipment_rule', 'slot' => 'weapon', 'maximum' => 2]])
         ],
         [
            'id' => 5,
            'name' => 'Layered Armor',
            'description' => 'Allows the user to use 2 armors.',
            'effect' => json_encode(['trigger' => 'equipment_check', 'effect' => ['type' => 'equipment_rule', 'slot' => 'armor', 'maximum' => 2]])
         ],
         [
            'id' => 6,
            'name' => 'Fire Guard',
            'description' => 'Reduce the amount of Fire damage taken.',
            'effect' => json_encode(['trigger' => 'taking_damage', 'effect' => ['type' => 'damage_modifier', 'applies_to' => ['affinity' => 'Fire'], 'operator' => '*', 'value' => 0.75]])
         ],
         [
            'id' => 7,
            'name' => 'Burning Resolve',
            'description' => 'The user becomes stronger when badly injured.',
            'effect' => json_encode(['trigger' => 'hp_changed', 'condition' => ['stat' => 'hp_percent', 'operator' => '<=', 'value' => 40], 'effects' => [['type' =>'stat_change', 'operator' => '*', 'stat' => 'attack', 'value' => 1.3, 'target' => 'self'], ['type' => 'enable_synergy', 'value' => true]], 'duration' => 'battle'])
         ],
         [
            'id' => 8,
            'name' => 'Coward',
            'description' => 'Will run away after 5 turns.',
            'effect' => json_encode(['trigger' => 'turn_end', 'condition' => ['turn' => 5], 'effect' => ['type' => 'flee', 'target' => 'self']])
         ]
      ]);
   }
}
