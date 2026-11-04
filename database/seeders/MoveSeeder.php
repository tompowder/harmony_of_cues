<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MoveSeeder extends Seeder
{
   /**
    * Run the database seeds.
    */
   public function run(): void
   {

      DB::table('moves')->insert(
      [
         [
            'id' => 1,
            'affinity_id' => 2, // water
            'name' => 'Water-Blast',
            'type' => 'magic',
            'power' => 2.1,
            'effect' => null,
            'description' => 'Deals magic Water damage to one enemy.',
            'cost' => 10,
            'category' => 'offense',
            'target' => 'enemy'
         ],
         [
            'id' => 2,
            'affinity_id' => 3, // nature
            'name' => 'Nature\'s-wrath',
            'type' => 'magic',
            'power' => 3,
            'effect' => null,
            'description' => 'Deals magic Nature damage to all enemies.',
            'cost' => 20,
            'category' => 'offense',
            'target' => 'enemies'
         ],
         [
            'id' => 3,
            'affinity_id' => 4, // none
            'name' => 'Arcane-Bolt',
            'type' => 'magic',
            'power' => 2,
            'effect' => null,
            'description' => 'Deals magic damage to one enemy.',
            'cost' => 15,
            'category' => 'offense',
            'target' => 'enemy'
         ],
         [
            'id' => 4,
            'affinity_id' => 1, // fire
            'name' => 'Flame-Point',
            'type' => 'magic',
            'power' => 2.7,
            'effect' => null,
            'description' => 'Deals magic Fire damage to one enemy.',
            'cost' => 13,
            'category' => 'offense',
            'target' => 'enemy'
         ],
         [
            'id' => 5,
            'affinity_id' => 1, // fire
            'name' => 'Fire-Burst',
            'type' => 'magic',
            'power' => 3,
            'effect' => null,
            'description' => 'Deals magic Fire damage to all enemies.',
            'cost' => 10,
            'category' => 'offense',
            'target' => 'enemies'
         ],
         [
            'id' => 6,
            'affinity_id' => 3, // nature
            'name' => 'Root-Hook',
            'type' => 'magic',
            'power' => 2.2,
            'effect' => null,
            'description' => 'Deals magic Nature damage to one enemy.',
            'cost' => 10,
            'category' => 'offense',
            'target' => 'enemy'
         ],
         [
            'id' => 7,
            'affinity_id' => 4, // none
            'name' => 'Heal',
            'type' => 'magic',
            'power' => 5,
            'effect' => json_encode(['type' => 'healing']),
            'description' => 'Restores HP.',
            'cost' => 15,
            'category' => 'support',
            'target' => 'self_or_ally'
         ],
         [
            'id' => 8,
            'affinity_id' => 4, // none
            'name' => 'Mass-Heal',
            'type' => 'magic',
            'power' => 4,
            'effect' => json_encode(['type' => 'healing']),
            'description' => 'Restores HP to all allies.',
            'cost' => 30,
            'category' => 'support',
            'target' => 'self_and_allies'
         ],
         [
            'id' => 9,
            'affinity_id' => 4, // none
            'name' => 'Guard',
            'type' => 'attack',
            'power' => 0.75,
            'effect' => json_encode(['type' => 'damage_modifier', 'applies_to' => ['move_type' => 'attack'], 'operator' => '*', 'value' => 0.75, 'duration' => ['value' => 5, 'unit' => 'turns']]),
            'description' => 'Reduces damage taken from attack moves.',
            'cost' => 2,
            'category' => 'defensive',
            'target' => 'self'
         ],
         [
            'id' => 10,
            'affinity_id' => 4, // none
            'name' => 'Magic-Barrier',
            'type' => 'magic',
            'power' => 0.75,
            'effect' => json_encode(['type' => 'damage_modifier', 'applies_to' => ['move_type' => 'magic'], 'operator' => '*', 'value' => 0.75, 'duration' => ['value' => 5, 'unit' => 'turns']]),
            'description' => 'Reduces damage taken from magic moves.',
            'cost' => 5,
            'category' => 'defensive',
            'target' => 'self'
         ],
         [ // ability move
            'id' => 11,
            'affinity_id' => 1, // fire
            'name' => 'Fiery-Retribution',
            'type' => 'piercing',
            'power' => 1,
            'effect' => null,
            'description' => 'Deals Fire piercing damage to an enemy. used by the ability Burning Body',
            'cost' => 0,
            'category' => 'offense',
            'target' => 'enemy'
         ],
         [ // ability move
            'id' => 12,
            'affinity_id' => 4, // none
            'name' => 'Bone-Jab',
            'type' => 'piercing',
            'power' => 1.2,
            'effect' => null,
            'description' => 'Deals piercing damage to an enemy. used by the ability Self Necromancy',
            'cost' => 0,
            'category' => 'offense',
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
            'category' => 'offense',
            'target' => 'enemy'
         ],
         [ // weapon move
            'id' => 14,
            'affinity_id' => 1, // fire
            'name' => 'Flame-Slash',
            'type' => 'attack',
            'power' => 4,
            'effect' => null,
            'description' => 'Deals Fire physical damage to one enemy.',
            'cost' => 10,
            'category' => 'offense',
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
            'category' => 'offense',
            'target' => 'enemy'
         ],
         [
            'id' => 16,
            'affinity_id' => 1, // fire
            'name' => 'Blazing-Kick',
            'type' => 'attack',
            'power' => 3,
            'effect' => null,
            'description' => 'Deals physical Fire damage to one enemy.',
            'cost' => 7,
            'category' => 'offense',
            'target' => 'enemy'
         ],
         [
            'id' => 17,
            'affinity_id' => 2, // water
            'name' => 'Water-Whip',
            'type' => 'attack',
            'power' => 3.5,
            'effect' => null,
            'description' => 'Deals physical Water damage to one enemy.',
            'cost' => 5,
            'category' => 'offense',
            'target' => 'enemy'
         ],
         [
            'id' => 18,
            'affinity_id' => 3, // nature
            'name' => 'Barbed-Upercut',
            'type' => 'attack',
            'power' => 3,
            'effect' => null,
            'description' => 'Deals physical Nature damage to one enemy.',
            'cost' => 6,
            'category' => 'offense',
            'target' => 'enemy'
         ],
         [
            'id' => 19,
            'affinity_id' => 5, // metal
            'name' => 'Metal-Clad-Charge',
            'type' => 'attack',
            'power' => 4.2,
            'effect' => null,
            'description' => 'Deals physical Metal damage to one enemy.',
            'cost' => 10,
            'category' => 'offense',
            'target' => 'enemy'
         ],
         [ // synergy move
            'id' => 20,
            'affinity_id' => 1, // fire
            'name' => 'Draco Inferno',
            'type' => 'attack',
            'power' => 5,
            'effect' => json_encode(['type' => 'stat_modifier', 'stat' => 'defense', 'operator' => '*', 'value' => 0.75, 'target' => 'enemy', 'duration' => ['value' => 2, 'unit' => 'turns']]),
            'description' => 'Deals powerful physical Fire damage and temporarily reduces the target\'s effective Defense.',
            'cost' => 15,
            'category' => 'offense',
            'target' => 'enemy'
         ],
         [ // synergy move
            'id' => 21,
            'affinity_id' => 2, // water
            'name' => 'Holy Water',
            'type' => 'magic',
            'power' => 4,
            'effect' => json_encode(['type' => 'use_move', 'target' => 'self_and_allies', 'move_id' => 5]),
            'description' => 'Deals magic Water damage and restores HP to all allies.',
            'cost' => 0,
            'category' => 'balanced',
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
            'category' => 'offense',
            'target' => 'enemies'
         ],
         [ // monster move
            'id' => 23,
            'affinity_id' => 4, // none
            'name' => 'Bite',
            'type' => 'attack',
            'power' => 2,
            'effect' => null,
            'description' => 'Deals physical damage to one enemy.',
            'cost' => 5,
            'category' => 'offense',
            'target' => 'enemy'
         ],
         [ // monster move
            'id' => 24,
            'affinity_id' => 3, // nature
            'name' => 'Nature-Scratch',
            'type' => 'attack',
            'power' => 2,
            'effect' => null,
            'description' => 'Deals Nature damage to one enemy.',
            'cost' => 5,
            'category' => 'offense',
            'target' => 'enemy'
         ],
         [ // monster move
            'id' => 25,
            'affinity_id' => 1, // fire
            'name' => 'Flare-Bash',
            'type' => 'attack',
            'power' => 3.2,
            'effect' => null,
            'description' => 'Deals Fire damage to one enemy.',
            'cost' => 7,
            'category' => 'offense',
            'target' => 'enemy'
         ],
         [ // monster move
            'id' => 26,
            'affinity_id' => 5, // metal 
            'name' => 'Iron-Claw',
            'type' => 'attack',
            'power' => 3,
            'effect' => null,
            'description' => 'Deals Metal damage to one enemy.',
            'cost' => 7,
            'category' => 'offense',
            'target' => 'enemy'
         ],
         [
            'id' => 27,
            'affinity_id' => 4, // none
            'name' => 'Pommel-Strike',
            'type' => 'attack',
            'power' => 1.5,
            'effect' => json_encode(['type' => 'stat_modifier', 'stat' => 'attack', 'operator' => '*', 'value' => 0.85, 'target' => 'enemy', 'duration' => ['value' => 2, 'unit' => 'turns']]),
            'description' => 'Deals physical damage to one enemy and temporarily reduces the target\'s effective Attack.',
            'cost' => 2,
            'category' => 'balanced',
            'target' => 'enemy'
         ],
         [ // synergy move
            'id' => 28,
            'affinity_id' => 4, // none
            'name' => 'Prismatic Beam',
            'type' => 'magic',
            'power' => 7,
            'effect' => json_encode(['type' => 'affinity_change', 'affinity' => 'user_affinity', 'target' => 'moves',  'duration' => ['value' => 1, 'unit' => 'moves']]),
            'description' => 'Deals powerful magic damage to one enemy. This move changes affinity depending on the users affinity.',
            'cost' => 0,
            'category' => 'offense',
            'target' => 'enemy'
         ]
      ]);
   }
}
