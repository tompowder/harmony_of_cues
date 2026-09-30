<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
   use WithoutModelEvents;

   /**
    * Seed the application's database.
    */
   public function run(): void
   {
      $userID = 1;
      $mapID = 1;

      DB::table('users')->insert([
         'id' => $userID,
         'name' => 'test',
         'email' => 'test@test.com',
         'password' => 'test'
      ]);

      DB::table('maps')->insert([
         'id' => $mapID,
         'name' => 'test',
         'map_data' => '----------------------\n|S . $|\n| --------- |\n| --| . | |\n| |># | . |\n| --| . |.. . .|\n| ---- | . |\n|. . . | |\n|I| . R | |\n-----------------<----'
      ]);

      DB::table('players')->insert([
         'id' => 1,
         'map_id' => $mapID,
         'user_id' => $userID,
         'gold' => 20,
         'x' => 17,
         'y' => 8,
      ]);

      DB::table('affinities')->insert([
         ['id' => 1, 'name' => 'Fire'],
         ['id' => 2, 'name' => 'Water'],
         ['id' => 3, 'name' => 'Nature'],
         ['id' => 4, 'name' => 'None'],
         ['id' => 5, 'name' => 'Metal']
      ]);

      $effective = 1.50;
      $weak = 0.75;

      DB::table('affinity_relationships')->insert([
         // fire on water
         ['id' => 1, 'affinity_id' => 1, 'target_affinity_id' => 2, 'multiplier' => $weak],
         // fire on metal
         ['id' => 2, 'affinity_id' => 1, 'target_affinity_id' => 5, 'multiplier' => $effective],
         // fire on nature
         ['id' => 3, 'affinity_id' => 1, 'target_affinity_id' => 3, 'multiplier' => $effective],
         // fire on none 
         ['id' => 4, 'affinity_id' => 1, 'target_affinity_id' => 4, 'multiplier' => $effective],

         // water on fire
         ['id' => 5, 'affinity_id' => 2, 'target_affinity_id' => 1, 'multiplier' => $effective],
         // water on nature 
         ['id' => 6, 'affinity_id' => 2, 'target_affinity_id' => 3, 'multiplier' => $weak],
         // water on metal
         ['id' => 7, 'affinity_id' => 2, 'target_affinity_id' => 5, 'multiplier' => $weak],

         // nature on fire
         ['id' => 8, 'affinity_id' => 3, 'target_affinity_id' => 1, 'multiplier' => $weak],
         // nature on water 
         ['id' => 9, 'affinity_id' => 3, 'target_affinity_id' => 2, 'multiplier' => $effective],
         // nature on metal 
         ['id' => 10, 'affinity_id' => 3, 'target_affinity_id' => 5, 'multiplier' => $effective],

         // none on metal
         ['id' => 11, 'affinity_id' => 4, 'target_affinity_id' => 5, 'multiplier' => $weak],
         // none on nature
         ['id' => 12, 'affinity_id' => 4, 'target_affinity_id' => 3, 'multiplier' => $effective],

         // metal on water
         ['id' => 13, 'affinity_id' => 5, 'target_affinity_id' => 2, 'multiplier' => $effective],
         // metal on nature
         ['id' => 14, 'affinity_id' => 5, 'target_affinity_id' => 3, 'multiplier' => $weak],
         // metal on fire
         ['id' => 16, 'affinity_id' => 5, 'target_affinity_id' => 1, 'multiplier' => $weak],
      ]);

      DB::table('abilities')->insert([
         [
            'id' => 1,
            'name' => 'Burning Body',
            'description' => 'Attackers receive damage when physically attacking the target',
            'effect' => '{ "trigger": "taking_damage", "condition": {"move_type": "attack"}, "effect": {"use_move": {"target": "attacker", "move_id": 9 }}}',
         ],
         [
            'id' => 2,
            'name' => 'Self Necromancy',
            'description' => 'When the user is defeated it will continue to attack until all of the users ally are defeated',
            'effect' => '{ "trigger": "defeated_skip", "effect": {"use_move": {"target": "random_enemy", "move_id": 10 }}}',
         ],
         [
            'id' => 3,
            'name' => 'Pack Hunter',
            'description' => 'Become stronger when another ally of the same type is alive.',
            'effect' => '{ "trigger": "encounter_start", "condition": {"same_type_allies_alive": true}, "update_trigger": "ally_defeated", "effect": { "stat_change": {"attack_multiplier": 1.30, "target": "self"}}}',
         ],
         [
            'id' => 4,
            'name' => 'dual wielding',
            'description' => 'Allows the user to use 2 weapons.',
            'effect' => '{ "trigger": "equip_weapon", "effect": {"weapon_amount": 2}}',
         ],
         [
            'id' => 5,
            'name' => 'layered armor',
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

      DB::table('moves')->insert([
         [
            'id' => 1,
            'affinity_id' => 2, // water
            'name' => 'Water blast',
            'type' => 'magic',
            'power' => 2.1,
            'effect' => null,
            'description' => 'Deals magic Water damage to one enemy.',
            'cost' => 10,
            'target' => 'enemy'
         ],
         [
            'id' => 2,
            'affinity_id' => 3, // nature
            'name' => 'Nature\'s wrath',
            'type' => 'magic',
            'power' => 3,
            'effect' => null,
            'description' => 'Deals magic Nature damage to all enemies.',
            'cost' => 20,
            'target' => 'enemies'
         ],
         [
            'id' => 3,
            'affinity_id' => 1, // fire
            'name' => 'Flame Point',
            'type' => 'magic',
            'power' => 2.7,
            'effect' => null,
            'description' => 'Deals magic Fire damage to one enemy.',
            'cost' => 13,
            'target' => 'enemy'
         ],
         [
            'id' => 4,
            'affinity_id' => 4, // none
            'name' => 'Heal',
            'type' => 'magic',
            'power' => 5,
            'effect' => '{"effect": "healing"}',
            'description' => 'Restores HP to an ally.',
            'cost' => 15,
            'target' => 'self_or_ally'
         ],
         [
            'id' => 5,
            'affinity_id' => 4, // none
            'name' => 'Mass heal',
            'type' => 'magic',
            'power' => 4,
            'effect' => '{"effect": "healing"}',
            'description' => 'Restores HP to all allies.',
            'cost' => 30,
            'target' => 'allies'
         ],
         [
            'id' => 6,
            'affinity_id' => 4, // none
            'name' => 'Tackle',
            'type' => 'attack',
            'power' => 2.5,
            'effect' => null,
            'description' => 'Deals physical damage to one enemy.',
            'cost' => 3,
            'target' => 'enemy'
         ],
         [
            'id' => 7,
            'affinity_id' => 4, // none
            'name' => 'Guard',
            'type' => 'attack',
            'power' => 0.75,
            'effect' => '{"effect": "damage_reduction", "duration": "5 turns" }',
            'description' => 'Reduces damage taken from attack moves.',
            'cost' => 2,
            'target' => 'self'
         ],
         [
            'id' => 8,
            'affinity_id' => 4, // none
            'name' => 'Magic guard',
            'type' => 'magic',
            'power' => 0.75,
            'effect' => '{"effect": "damage_reduction", "duration": "5 turns" }',
            'description' => 'Reduces damage taken from magic moves.',
            'cost' => 5,
            'target' => 'self'
         ],
         [
            'id' => 9,
            'affinity_id' => 1, // fire
            'name' => 'Fiery retribution',
            'type' => 'piercing',
            'power' => 1,
            'effect' => null,
            'description' => 'Deals Fire piercing damage to an enemy. used by the ability Burning Body',
            'cost' => 0,
            'target' => 'enemy'
         ],
         [
            'id' => 10,
            'affinity_id' => 4, // none
            'name' => 'Bone jab',
            'type' => 'piercing',
            'power' => 1.2,
            'effect' => null,
            'description' => 'Deals piercing damage to an enemy. used by the ability Self Necromancy',
            'cost' => 0,
            'target' => 'enemy'
         ],
         [
            'id' => 11,
            'affinity_id' => 5, // metal
            'name' => 'Slashing',
            'type' => 'attack',
            'power' => 3.2,
            'effect' => null,
            'description' => 'Deals Metal physical damage to one enemy.',
            'cost' => 5,
            'target' => 'enemy'
         ],
         [
            'id' => 12,
            'affinity_id' => 1, // fire
            'name' => 'Flame Slash',
            'type' => 'attack',
            'power' => 3.2,
            'effect' => null,
            'description' => 'Deals Fire physical damage to one enemy.',
            'cost' => 5,
            'target' => 'enemy'
         ],
         [
            'id' => 13,
            'affinity_id' => 1, // fire
            'name' => 'Fire Burst',
            'type' => 'magic',
            'power' => 3,
            'effect' => null,
            'description' => 'Deals magic Fire damage to all enemies.',
            'cost' => 10,
            'target' => 'enemies'
         ],
         [
            'id' => 14,
            'affinity_id' => 3, // nature
            'name' => 'Root hook',
            'type' => 'magic',
            'power' => 2.2,
            'effect' => null,
            'description' => 'Deals magic Nature damage to one enemy.',
            'cost' => 10,
            'target' => 'enemy'
         ],
         [
            'id' => 15,
            'affinity_id' => 1, // fire
            'name' => 'Draco Inferno',
            'type' => 'attack',
            'power' => 5,
            'effect' => '{"extra_effect": {"defense_multiplier": 0.75, "target": "enemy"}, "duration": "1 turns"}',
            'description' => 'Deals powerful physical Fire damage and temporarily reduces the target\'s effective Defense.',
            'cost' => 15,
            'target' => 'enemy'
         ],
         [
            'id' => 16,
            'affinity_id' => 2, // water
            'name' => 'Holy Water',
            'type' => 'magic',
            'power' => 4,
            'effect' => '{"use_move": {"target": "allies", "move_id": 5 }}',
            'description' => 'Deals magic Water damage and restores HP to all allies.',
            'cost' => 0,
            'target' => 'enemies'
         ],
         [
            'id' => 17,
            'affinity_id' => 1, // fire
            'name' => 'Burning Catastrophe',
            'type' => 'magic',
            'power' => 5.5,
            'effect' => null,
            'description' => 'Deals powerful magic Fire damage to all enemies.',
            'cost' => 0,
            'target' => 'enemies'
         ]
      ]);

      DB::table('synergies')->insert([
         [
            'id' => 1,
            'move_id' => 15, // Draco Inferno
            'calculation' => '{"stat": "attack", "method": "highest"}'
         ],
         [
            'id' => 2,
            'move_id' => 16, // Holy Water
            'calculation' => '{"stat": "magic", "method": "highest"}'
         ],
         [
            'id' => 3,
            'move_id' => 17, // Burning Catastrophe
            'calculation' => '{"stat": "magic", "method": "average"}'
         ]

      ]);

      DB::table('synergy_moves')->insert([
         [
            'id' => 1,
            'move_id' => 12, // Flame Slash
            'synergy_id' => 1, // Draco Inferno
            'participant' => 'self'
         ],
         [
            'id' => 2,
            'move_id' => 4, // Heal
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
            'move_id' => 3, // Flame Point
            'synergy_id' => 3, // Burning Catastrophe
            'participant' => 'ally'
         ]
      ]);

      DB::table('level_schemas')->insert([
         [
            'id' => 1,
            'max_hp' => 800,
            'max_mp' => 100,
            'max_attack' => 300,
            'max_magic' => 50,
            'max_defense' => 200,
            'max_xp' => 10000
         ]
      ]);
   }
}
