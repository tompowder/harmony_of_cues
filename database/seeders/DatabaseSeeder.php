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

      // new map design wear x is /2 so the player feel like there moving the same distance in the x and y direction.
      // and the player moves x 2 places and for y move 1 place
      //[
      //   "- - - - - - - - - - - -\n",
      //   "| S       .          $|\n",
      //   "|     - - - - -       |\n",
      //   "|   - |   .   |       |\n",
      //   "|   | > #     |     . |\n",
      //   "|   - |  .    | .     |\n",
      //   "|     - - - . |   .   |\n",
      //   "| .     .   . |       |\n",
      //   "| I |   .   R |       |\n",
      //   "- - - - - - - - - - - -\n"
      //];

      //[
      //   "- - < - - - - - - - - - - - - - -\n",
      //   "|       |               .       |\n",
      //   "|       |   #           .       |\n",
      //   "|       |               .       |\n",
      //   "| . . . | - - - - - - - - . . . |\n",
      //   "|       | - - - - - - - -       |\n",
      //   "|       .       .       .       |\n",
      //   "$       .       .       .       |\n",
      //   "- - S - - - - - - - - - - - - - -\n"
      //];

      //[
      //   "- - - - - - - - - - - - - - - S -\n",
      //   "|           .       .           |\n",
      //   "|           .       .           |\n",
      //   "|         - - - - - - - R . . . |\n",
      //   "I       | | - - | | | | |       |\n",
      //   "|       $ | - - - - - - -       |\n",
      //   "|       | | - -         .       |\n",
      //   "|       | | >           .       |\n",
      //   "- - < - - - - - - - - - - - - - -\n"
      //];

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

      DB::table('moves')->insert([
         [
            'id' => 1,
            'affinity_id' => 2, // water
            'name' => 'Water Blast',
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
            'affinity_id' => 4, // none
            'name' => 'Arcane Bolt',
            'type' => 'magic',
            'power' => 2,
            'effect' => null,
            'description' => 'Deals magic damage to one enemy.',
            'cost' => 15,
            'target' => 'enemy'
         ],
         [
            'id' => 4,
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
            'id' => 5,
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
            'id' => 6,
            'affinity_id' => 3, // nature
            'name' => 'Root Hook',
            'type' => 'magic',
            'power' => 2.2,
            'effect' => null,
            'description' => 'Deals magic Nature damage to one enemy.',
            'cost' => 10,
            'target' => 'enemy'
         ],
         [
            'id' => 7,
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
            'id' => 8,
            'affinity_id' => 4, // none
            'name' => 'Mass Heal',
            'type' => 'magic',
            'power' => 4,
            'effect' => '{"effect": "healing"}',
            'description' => 'Restores HP to all allies.',
            'cost' => 30,
            'target' => 'allies'
         ],
         [
            'id' => 9,
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
            'id' => 10,
            'affinity_id' => 4, // none
            'name' => 'Magic Guard',
            'type' => 'magic',
            'power' => 0.75,
            'effect' => '{"effect": "damage_reduction", "duration": "5 turns" }',
            'description' => 'Reduces damage taken from magic moves.',
            'cost' => 5,
            'target' => 'self'
         ],
         [ // ability move
            'id' => 11,
            'affinity_id' => 1, // fire
            'name' => 'Fiery Retribution',
            'type' => 'piercing',
            'power' => 1,
            'effect' => null,
            'description' => 'Deals Fire piercing damage to an enemy. used by the ability Burning Body',
            'cost' => 0,
            'target' => 'enemy'
         ],
         [ // ability move
            'id' => 12,
            'affinity_id' => 4, // none
            'name' => 'Bone Jab',
            'type' => 'piercing',
            'power' => 1.2,
            'effect' => null,
            'description' => 'Deals piercing damage to an enemy. used by the ability Self Necromancy',
            'cost' => 0,
            'target' => 'enemy'
         ],
         [ // weapon move
            'id' => 13,
            'affinity_id' => 5, // metal
            'name' => 'Slashing',
            'type' => 'attack',
            'power' => 3.2,
            'effect' => null,
            'description' => 'Deals Metal physical damage to one enemy.',
            'cost' => 5,
            'target' => 'enemy'
         ],
         [ // weapon move
            'id' => 14,
            'affinity_id' => 1, // fire
            'name' => 'Flame Slash',
            'type' => 'attack',
            'power' => 4,
            'effect' => null,
            'description' => 'Deals Fire physical damage to one enemy.',
            'cost' => 10,
            'target' => 'enemy'
         ],
         [
            'id' => 15,
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
            'id' => 16,
            'affinity_id' => 1, // fire
            'name' => 'Blazing Kick',
            'type' => 'attack',
            'power' => 3,
            'effect' => null,
            'description' => 'Deals physical Fire damage to one enemy.',
            'cost' => 7,
            'target' => 'enemy'
         ],
         [
            'id' => 17,
            'affinity_id' => 2, // water
            'name' => 'Water Whip',
            'type' => 'attack',
            'power' => 3.5,
            'effect' => null,
            'description' => 'Deals physical Water damage to one enemy.',
            'cost' => 5,
            'target' => 'enemy'
         ],
         [
            'id' => 18,
            'affinity_id' => 3, // nature
            'name' => 'Barbed Upercut',
            'type' => 'attack',
            'power' => 3,
            'effect' => null,
            'description' => 'Deals physical Nature damage to one enemy.',
            'cost' => 6,
            'target' => 'enemy'
         ],
         [
            'id' => 19,
            'affinity_id' => 5, // metal
            'name' => 'Metal Clad Charge',
            'type' => 'attack',
            'power' => 4.2,
            'effect' => null,
            'description' => 'Deals physical Metal damage to one enemy.',
            'cost' => 10,
            'target' => 'enemy'
         ],
         [ // synergy move
            'id' => 20,
            'affinity_id' => 1, // fire
            'name' => 'Draco Inferno',
            'type' => 'attack',
            'power' => 5,
            'effect' => '{"extra_effect": {"defense_multiplier": 0.75, "target": "enemy"}, "duration": "1 turns"}',
            'description' => 'Deals powerful physical Fire damage and temporarily reduces the target\'s effective Defense.',
            'cost' => 15,
            'target' => 'enemy'
         ],
         [ // synergy move
            'id' => 21,
            'affinity_id' => 2, // water
            'name' => 'Holy Water',
            'type' => 'magic',
            'power' => 4,
            'effect' => '{"use_move": {"target": "allies", "move_id": 5 }}',
            'description' => 'Deals magic Water damage and restores HP to all allies.',
            'cost' => 0,
            'target' => 'enemies'
         ],
         [ // synergy move
            'id' => 22,
            'affinity_id' => 1, // fire
            'name' => 'Burning Catastrophe',
            'type' => 'magic',
            'power' => 5.5,
            'effect' => null,
            'description' => 'Deals powerful magic Fire damage to all enemies.',
            'cost' => 0,
            'target' => 'enemies'
         ],
         [ // monster move
            'id' => 23,
            'affinity_id' => 4, // none
            'name' => 'bite',
            'type' => 'attack',
            'power' => 2,
            'effect' => null,
            'description' => 'Deals physical damage to one enemy.',
            'cost' => 5,
            'target' => 'enemy'
         ],
         [ // monster move
            'id' => 24,
            'affinity_id' => 3, // nature
            'name' => 'nature scratch',
            'type' => 'attack',
            'power' => 2,
            'effect' => null,
            'description' => 'Deals Nature damage to one enemy.',
            'cost' => 5,
            'target' => 'enemy'
         ],
         [ // monster move
            'id' => 25,
            'affinity_id' => 1, // fire
            'name' => 'fire bash',
            'type' => 'attack',
            'power' => 3,
            'effect' => null,
            'description' => 'Deals Fire damage to one enemy.',
            'cost' => 7,
            'target' => 'enemy'
         ],
         [ // monster move
            'id' => 26,
            'affinity_id' => 5, // metal 
            'name' => 'metal claw',
            'type' => 'attack',
            'power' => 3,
            'effect' => null,
            'description' => 'Deals Metal damage to one enemy.',
            'cost' => 7,
            'target' => 'enemy'
         ],
         [
            'id' => 27,
            'affinity_id' => 4, // none
            'name' => 'Pommel Strike',
            'type' => 'attack',
            'power' => 2.7,
            'effect' => null,
            'description' => 'Deals physical damage to one enemy.',
            'cost' => 5,
            'target' => 'enemy'
         ]
      ]);

      DB::table('synergies')->insert([
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

      DB::table('synergy_moves')->insert([
         [
            'id' => 1,
            'move_id' => 15, // Flame Slash
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

      DB::table('level_schemas')->insert([
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
            'max_mp' => 0,
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
            'max_mp' => 200,
            'max_attack' => 400,
            'max_magic' => 400,
            'max_defense' => 300,
            'max_xp' => 25000
         ]
      ]);

      DB::table('level_schema_moves')->insert([
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
            'level_schema_id' => 4, // slime
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
            'id' => 20,
            'level_schema_id' => 6, // metal slime
            'move_id' => 9, // guard
            'level_requirement' => 1
         ],
         [
            'id' => 20,
            'level_schema_id' => 6, // metal slime
            'move_id' => 10, // magic guard
            'level_requirement' => 1
         ],
         [
            'id' => 21,
            'level_schema_id' => 7, // fire slime
            'move_id' => 15, // Tackle
            'level_requirement' => 1
         ],
         [
            'id' => 22,
            'level_schema_id' => 7, // fire slime
            'move_id' => 25, // fire bash
            'level_requirement' => 1
         ],
         [
            'id' => 23,
            'level_schema_id' => 8, // forest wolf
            'move_id' => 15, // Tackle
            'level_requirement' => 1
         ],
         [
            'id' => 24,
            'level_schema_id' => 8, // forest wolf
            'move_id' => 24, // nature scratch
            'level_requirement' => 1   
         ],
         [
            'id' => 25,
            'level_schema_id' => 8, // forest wolf
            'move_id' => 23, // bite
            'level_requirement' => 1   
         ],
         [
            'id' => 26,
            'level_schema_id' => 9, // water djinn
            'move_id' => 1, // water blast
            'level_requirement' => 1   
         ],
         [
            'id' => 27,
            'level_schema_id' => 9, // water djinn
            'move_id' => 17, // water whip
            'level_requirement' => 1   
         ],
         [
            'id' => 28,
            'level_schema_id' => 9, // water djinn
            'move_id' => 7, // heal
            'level_requirement' => 1   
         ],
         [
            'id' => 29,
            'level_schema_id' => 9, // water djinn
            'move_id' => 2, // nature's wrath
            'level_requirement' => 1   
         ],
         [
            'id' => 30,
            'level_schema_id' => 10, // infernal knight
            'move_id' => 4, // flame point
            'level_requirement' => 1   
         ],
         [
            'id' => 31,
            'level_schema_id' => 10, // infernal knight
            'move_id' => 16, // blazing kick
            'level_requirement' => 1   
         ],
         [
            'id' => 32,
            'level_schema_id' => 10, // infernal knight
            'move_id' => 27, // pommel strike
            'level_requirement' => 1
         ],
         [
            'id' => 33,
            'level_schema_id' => 10, // infernal knight
            'move_id' => 9, // guard
            'level_requirement' => 1
         ],
         [
            'id' => 34,
            'level_schema_id' => 10, // infernal knight
            'move_id' => 5, // fire burst
            'level_requirement' => 1
         ]
      ]);

      DB::table('entities')->insert([
         [
            'id' => 1,
            'level_schema_id' => 1, // attack hero
            'affinity_id' => 4, // none
            'ability_id' => 4, // dual wielding
            'name' => 'jonathan the warrior',
         ],
         [
            'id' => 2,
            'level_schema_id' => 2, // magic hero
            'affinity_id' => 4, // none
            'ability_id' => 5, // layered armor
            'name' => 'rosa the mage',
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
         ]
      ]);

      $boss_ai = '
      { "phases": [ 
         { 
            "name": "phase_1", 
            "condition": 
            { 
               "stat": "hp_percent", 
               "operator": ">", 
               "value": 75 
            }, 
            "moves": [], 
            "move_selection": "careful" 
         }, 
         { 
            "name": "phase_2", 
            "condition": 
            { 
               "stat": "hp_percent", 
               "operator": "<=", 
               "value": 75
            }, 
            "moves": [], 
            "move_selection": "random"
         }, 
         { 
            "name": "phase_3", 
            "condition": 
            { 
               "stat": "hp_percent", 
               "operator": "<=", 
               "value": 40 
            }, 
            "moves": [], 
            "move_selection": "reckless" 
         }] 
      }';

      DB::table('equipment')->insert([
         [
            'id' => 1,
            'affinity_id' => null, 
            'ability_id' => null,
            'move_id' => 13, // slashing
            'type' => 'weapon',
            'name' => 'Iron Sword',
            'boost_hp' => 0,
            'boost_mp' => 0,
            'attack_bonus' => 50,
            'magic_bonus' => 0,
            'defense_bonus' => 0,
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
            'attack_bonus' => 75,
            'magic_bonus' => 0,
            'defense_bonus' => 0,
         ],
         [
            'id' => 3,
            'affinity_id' => null, 
            'ability_id' => null,
            'move_id' => 15, // water blast
            'type' => 'weapon',
            'name' => 'Water Staff',
            'boost_hp' => 0,
            'boost_mp' => 15,
            'attack_bonus' => 0,
            'magic_bonus' => 75,
            'defense_bonus' => 0,
         ],
         [
            'id' => 4,
            'affinity_id' => 5, // metal
            'ability_id' => null,
            'move_id' => null,
            'type' => 'armor',
            'name' => 'Steel Armor',
            'boost_hp' => 0,
            'boost_mp' => 0,
            'attack_bonus' => 0,
            'magic_bonus' => 0,
            'defense_bonus' => 75,
         ],
         [
            'id' => 5,
            'affinity_id' => 1, // fire
            'ability_id' => 6, // fire guard
            'move_id' => null,
            'type' => 'armor',
            'name' => 'Fire Charm Armor',
            'boost_hp' => 0,
            'boost_mp' => 0,
            'attack_bonus' => 15,
            'magic_bonus' => 10,
            'defense_bonus' => 60,
         ],
         [
            'id' => 6,
            'affinity_id' => 4, // none
            'ability_id' => null,
            'move_id' => null,
            'type' => 'armor',
            'name' => 'Leather Armor',
            'boost_hp' => 20,
            'boost_mp' => 0,
            'attack_bonus' => 15,
            'magic_bonus' => 0,
            'defense_bonus' => 25,
         ],
         [
            'id' => 7,
            'affinity_id' => 4, // none
            'ability_id' => null,
            'move_id' => null,
            'type' => 'armor',
            'name' => 'Robe',
            'boost_hp' => 10,
            'boost_mp' => 20,
            'attack_bonus' => 0,
            'magic_bonus' => 30,
            'defense_bonus' => 20,
         ],
         [
            'id' => 8,
            'affinity_id' => 2, // water
            'ability_id' => null,
            'move_id' => null,
            'type' => 'armor',
            'name' => 'Water Robe',
            'boost_hp' => 30,
            'boost_mp' => 0,
            'attack_bonus' => 20,
            'magic_bonus' => 0,
            'defense_bonus' => 50,
         ],
         [
            'id' => 9,
            'affinity_id' => 1, // fire
            'ability_id' => null,
            'move_id' => null,
            'type' => 'armor',
            'name' => 'Fire Robe',
            'boost_hp' => 20,
            'boost_mp' => 20,
            'attack_bonus' => 0,
            'magic_bonus' => 20,
            'defense_bonus' => 40,
         ]
      ]);

      DB::table('model_monsters')->insert([
         [
            'id' => 1,
            'entity_id' => 3, // goblin
            'gold_drop' => 5,
            'xp_drop' => 100,
            'boss' => false,
            'ai' => '{"move_selection": "random" }'
         ],
         [
            'id' => 2,
            'entity_id' => 4, // golden goblin
            'gold_drop' => 20,
            'xp_drop' => 500,
            'boss' => false,
            'ai' => '{"move_selection": "random" }'
         ],
         [
            'id' => 3,
            'entity_id' => 5, // slime
            'gold_drop' => 3,
            'xp_drop' => 50,
            'boss' => false,
            'ai' => '{"move_selection": "random" }'
         ],
         [
            'id' => 4,
            'entity_id' => 6, // metal slime
            'gold_drop' => 10,
            'xp_drop' => 750,
            'boss' => false,
            'ai' => '{"move_selection": "random" }'
         ],
         [
            'id' => 5,
            'entity_id' => 7, // fire slime
            'gold_drop' => 8,
            'xp_drop' => 200,
            'boss' => false,
            'ai' => '{"move_selection": "random" }'
         ],
         [
            'id' => 6,
            'entity_id' => 8, // forest wolf
            'gold_drop' => 12,
            'xp_drop' => 300,
            'boss' => false,
            'ai' => '{"move_selection": "random" }'
         ],
         [
            'id' => 7,
            'entity_id' => 9, // water djinn
            'gold_drop' => 15,
            'xp_drop' => 400,
            'boss' => false,
            'ai' => '{"move_selection": "random" }'
         ],
         [
            'id' => 8,
            'entity_id' => 10, // infernal knight
            'gold_drop' => 50,
            'xp_drop' => 1000,
            'boss' => true,
            'ai' => $boss_ai
         ]
      ]);

      
      
   }
}
