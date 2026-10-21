<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class EncounterService
{
   public function loadEncounter()
   {
      $player = session()->get('player');

      $encounters = DB::table('encounters')->where('map_id', '=', $player['map_id'])->get();
      $roll = random_int(1, 100);
      $chance = 0;
      $encounter = null;

      foreach ($encounters as $candidate) {
         $chance += (int) $candidate->encounter_chance;

         if ($roll <= $chance) {
            $encounter = $candidate;
            break;
         }
      }

      $totalMonsterData = array();

      $monsters = DB::table('encounter_monsters')->select(DB::raw("level_schemas.id as level_id, level_schemas.max_hp, level_schemas.max_mp, level_schemas.max_attack, level_schemas.max_magic, level_schemas.max_defense,
      affinities.id as affinity_id, affinities.name as affinity_name, abilities.name as ability_name, abilities.effect, model_monsters.gold_drop, model_monsters.xp_drop, 
      model_monsters.id as monster_id, model_monsters.boss, model_monsters.ai, entities.name as monster_name, encounter_monsters.quantity, encounter_monsters.min_level, encounter_monsters.max_level,
      min_level AS random_level"))->
      leftJoin('model_monsters', 'encounter_monsters.model_monster_id', '=', 'model_monsters.id')->
      leftJoin('entities', 'model_monsters.entity_id', '=', 'entities.id')->
      leftJoin('level_schemas', 'entities.level_schema_id', '=', 'level_schemas.id')->
      leftJoin('affinities', 'entities.affinity_id', '=', 'affinities.id')->
      leftJoin('abilities', 'entities.ability_id', '=', 'abilities.id')->
      where('encounter_monsters.encounter_id', '=', $encounter->id)->get();
      
      foreach($monsters as $monster)
      {
         $completeMonster = ['monsterData' => $monster, 'monsterEquipment' => null, 'monsterMoves' => null];

         $monsterEquipment = DB::table('equipment_monsters')->select(DB::raw("equipment.id as equipment_id, equipment_monsters.drop_chance, equipment.name as equipment_name, equipment.type, equipment.move_id,
         affinities.id as affinity_id, affinities.name as affinity_name, abilities.name as ability_name, abilities.effect,
         equipment.boost_hp, equipment.boost_mp, equipment.boost_attack, equipment.boost_magic, equipment.boost_defense"))->
         leftJoin('equipment', 'equipment_monsters.equipment_id', '=', 'equipment.id')->
         leftJoin('affinities', 'equipment.affinity_id', '=', 'affinities.id')->
         leftJoin('abilities', 'equipment.ability_id', '=', 'abilities.id')->
         where('equipment_monsters.model_monster_id', '=', $monster->monster_id)->get(); 

         $completeMonster['monsterEquipment'] = $monsterEquipment;
         $weaponMove = null;

         foreach($monsterEquipment as $monsterEquipment)
         {
            if($monsterEquipment->move_id)
            {$weaponMove = $monsterEquipment->move_id;}
         }
         
         $monster->random_level = random_int($monster->min_level, $monster->max_level);

         $monsterMoves = DB::table('moves')->select(DB::raw("level_schema_moves.level_requirement, moves.name as move_name, moves.type, 
         moves.id as move_id, moves.power, moves.effect, moves.cost, moves.target, affinities.id as affinity_id, affinities.name as affinity_name"))->
         leftJoin('level_schema_moves', 'moves.id', '=', 'level_schema_moves.move_id')->
         leftJoin('affinities', 'moves.affinity_id', '=', 'affinities.id')->
         where('level_schema_moves.level_schema_id', '=', $monster->level_id)->
         where('level_requirement', '<=', $monster->random_level)->
         orWhere('moves.id', '=', $weaponMove)->
         orderBy('level_requirement', 'desc');

         if($monster->boss)
         {$monsterMoves = $monsterMoves->get();}
         else
         {$monsterMoves = $monsterMoves->limit(4)->get();}

         $completeMonster['monsterMoves'] = $monsterMoves;

         $totalMonsterData[] = $completeMonster;
      }

      $totalheroData = array();

      $heroes = DB::table('saved_heroes')->select(DB::raw("level_schemas.id as level_id, level_schemas.max_hp, level_schemas.max_mp, level_schemas.max_attack, level_schemas.max_magic, level_schemas.max_defense,
      hero_stat_points.stat_points_hp, hero_stat_points.stat_points_mp, hero_stat_points.stat_points_attack, hero_stat_points.stat_points_magic, hero_stat_points.stat_points_defense,
      saved_heroes.level, saved_heroes.xp, saved_heroes.id as hero_id, affinities.id as affinity_id, affinities.name as affinity_name, abilities.name as ability_name, abilities.name as ability_name, abilities.effect, entities.name as hero_name" ))->
      leftJoin('model_heroes', 'saved_heroes.model_hero_id', '=', 'model_heroes.id')->
      leftJoin('entities', 'model_heroes.entity_id', '=', 'entities.id')->
      leftJoin('level_schemas', 'entities.level_schema_id', '=', 'level_schemas.id')->
      leftJoin('affinities', 'entities.affinity_id', '=', 'affinities.id')->
      leftJoin('abilities', 'entities.ability_id', '=', 'abilities.id')->
      leftJoin('hero_stat_points', 'saved_heroes.hero_stat_point_id', '=', 'hero_stat_points.id')->
      where('saved_heroes.player_id', '=', $player['id'])->get();

      foreach($heroes as $hero)
      {
         $totalhero = ['heroData' => $hero, 'heroEquipment' => null, 'heroMoves' => null];

         $heroEquipment = DB::table('equipment_saved_hero')->select(DB::raw("equipment.id as equipment_id, equipment.name as equipment_name, equipment.type, equipment.move_id,
         affinities.id as affinity_id, affinities.name as affinity_name, abilities.name as ability_name, abilities.effect,
         equipment.boost_hp, equipment.boost_mp, equipment.boost_attack, equipment.boost_magic, equipment.boost_defense"))->
         leftJoin('inventories', 'equipment_saved_hero.id', '=', 'inventories.equipment_id')->
         leftJoin('equipment', 'inventories.equipment_id', '=', 'equipment.id')->
         leftJoin('affinities', 'equipment.affinity_id', '=', 'affinities.id')->
         leftJoin('abilities', 'equipment.ability_id', '=', 'abilities.id')->
         where('equipment_saved_hero.saved_hero_id', '=', $hero->hero_id)->
         where('inventories.player_id', '=', $player['id'])->get();

         $totalhero['heroEquipment'] = $heroEquipment;
         $weaponMove = null;

         foreach($heroEquipment as $heroEquipment)
         {
            if($heroEquipment->move_id)
            {$weaponMove = $heroEquipment->move_id;}
         }

         $heroMoves = DB::table('move_saved_heroes')->select(DB::raw('move_saved_heroes.slot, move_saved_heroes.source, moves.name as move_name, moves.type, 
         moves.id as move_id, moves.power, moves.effect, moves.cost, moves.target, affinities.id as affinity_id, affinities.name as affinity_name'))->
         leftJoin('moves', 'move_saved_heroes.move_id', '=', 'moves.id')->
         leftJoin('affinities', 'moves.affinity_id', '=', 'affinities.id')->
         where('saved_heroes_id', '=', $hero->hero_id)->get();

         $totalhero['heroMoves'] = $heroMoves;
         $totalheroData[] = $totalhero;
      }

      $monsters = $this->processMonster($totalMonsterData);
      $heroes = $this->processHero($totalheroData);

      $heroes[0]['selected_hero'] = true;

      session(
      [
         'encounter' => 
         [
            'heroes' => $heroes,
            'monsters' => $monsters,
            'turn' => 1,
            'phase' => 'hero_actions',
         ],
      ]);

      return $this->displayEncounter($monsters, $heroes);
   }

   private function displayEncounter(array $monsters, array &$heroes, array $selected = ['x' => 0, 'y' => 0])
   {
      // out put exsampel
      // ------------ ----------
      // | goblin   | | slime  |
      // | HP 24/24 | | HP 6/6 |
      // ------------ ----------
      // ---------------- ----------
      // | jonathan     | | rosa   |
      // | HP 8/8       | | HP 6/6 |
      // | MP 1/1       | | MP 7/7 |
      // |*Attack Magic | ----------
      // | Run    Skip  |
      // ----------------

      $monsterBoxes = array_map(fn(array $monster) => $this->createBox(
      [
         " {$monster['monster_name']} ",
         " HP {$monster['current_stats']['hp']}/{$monster['total_stats']['hp']} ",
      ]), $monsters);
      
      $heroBoxes = [];

      foreach ($heroes as &$hero) 
      {
         $box =
         [
            " {$hero['hero_name']} ",
            " HP {$hero['current_stats']['hp']}/{$hero['total_stats']['hp']} ",
            " MP {$hero['current_stats']['mp']}/{$hero['total_stats']['mp']} "
         ];

         if($hero['selected_hero'])
         {
            $options = $this->getHeroOptions($hero);

            $options = array_map(fn (string $option) => ' ' . $option, $options);

            $options = array_chunk($options, 2);

            $options = array_pad($options, 2, []); // can be better

            $options = array_map(fn (array $row) => array_pad($row, 2, ''), $options); // can be better

            $x = $selected['x'];
            $y = $selected['y'];

            $options[$y][$x] = str_replace(' ', '*', $options[$y][$x]) ;  // can be better

            $box[] = "{$options[0][0]}{$options[0][1]} ";  // can be better
            $box[] = "{$options[1][0]}{$options[1][1]} ";

         }

         $heroBoxes[] = $this->createBox($box);
      }

      $alleBoxes = array_merge($this->combineBoxes($monsterBoxes), $this->combineBoxes($heroBoxes));

      return ["display" => $alleBoxes, "coordinates" => $selected];
   }

   private function getHeroOptions(array $hero)
   {
      $selectedOption = $hero['selected_options'] ?? null;
      $selectedMove = $hero['selected_move'] ?? null;

      if (in_array($selectedOption, ['Attack', 'Magic'], true) && $selectedMove === null) 
      {
         $moveType = strtolower($hero['selected_options']);

         $moves = array_filter($hero['moves'], fn($move) => $move['type'] === $moveType);
         $moveNames = array_map(fn($move) => $move['move_name'], $moves);

         return $moveNames;
      } 

      return ['Attack', 'Magic', 'Run', 'Skip'];
   }

   public function createBox(array $content)
   {
      $width = max(array_map('strlen', $content));

      $LeftRichtborder = 2;

      $topBottomBorder = str_repeat('-', $width + $LeftRichtborder);

      $lines = array_map(fn (string $line) => '|' . str_pad($line, $width) . '|', $content);

      array_unshift($lines, $topBottomBorder);
      $lines[] = $topBottomBorder;

      return $lines;
   }

   private function combineBoxes(array $boxes)
   {
      $maxLines = max(array_map('count', $boxes));
      $lines = [];

      for ($i = 0; $i < $maxLines; $i++)
      {
         $lines[] = implode(' ', array_map(fn($box) => $box[$i], $boxes));
      }

      $lines = array_map(fn(string $line) => str_replace(' ', '&nbsp', $line), $lines);

      return $lines;
   }

   private function processHero(array $rawDataHero)
   {
      $processedHeroes = array();

      foreach ($rawDataHero as $data) 
      {
         $Hdata = $data['heroData'];
         $EData = $data['heroEquipment'];
         $MData = $data['heroMoves'];

         $hero =
         [
            'instance_id' => count($processedHeroes),
            'hero_id' => $Hdata->hero_id,
            'hero_name' => $Hdata->hero_name,
            'level' => $Hdata->level,
            'xp' => $Hdata->xp,
            'base_stats' => [],
            'points_stats' => [],
            'equipment_stats' => [],
            'total_stats' => [],
            'affinity' => ['affinity_id' => $Hdata->affinity_id, 'affinity_name' => $Hdata->affinity_name],
            'ability' => ['ability_name' => $Hdata->ability_name, 'ability_effect' => $Hdata->effect],
            'equipment' => $this->processEquipment($EData),
            'moves' => $this->processMove($MData),
            'selected_hero' => false,
            'selected_options' => null,
            'selected_move' => null
         ];

         $stats = $this->calculateStats($hero, $Hdata);

         $hero['base_stats'] = $stats['base_stats'];
         $hero['equipment_stats'] = $stats['equipment_stats'];
         $hero['points_stats'] = $stats['points_stats'];
         $hero['total_stats'] = $stats['total_stats'];
         $hero['current_stats'] = $stats['current_stats'];

         $processedHeroes[] = $hero;
      }

      return $processedHeroes;
   }

   private function processMonster(array $rawDataMonster)
   {
      $processedMonsters = array();

      foreach ($rawDataMonster as $data) 
      {
         $Mondata = $data['monsterData'];
         $EData = $data['monsterEquipment'];
         $MData = $data['monsterMoves'];

         $monster =
         [
            'instance_id' => count($processedMonsters),
            'monster_id' => $Mondata->monster_id,
            'monster_name' => $Mondata->monster_name,
            'level' => $Mondata->random_level,
            'base_stats' => [],
            'equipment_stats' => [],
            'total_stats' => [],
            'current_stats' => [],
            'affinity' => ['affinity_id' => $Mondata->affinity_id, 'affinity_name' => $Mondata->affinity_name],
            'ability' => ['ability_name' => $Mondata->ability_name, 'ability_effect' => $Mondata->effect],
            'gold_drop' => $Mondata->gold_drop,
            'xp_drop' => $Mondata->xp_drop,
            'boss' => $Mondata->boss,
            'ai' => $Mondata->ai,
            'equipment' => $this->processEquipment($EData),
            'moves' => $this->processMove($MData)
         ];

         $stats = $this->calculateStats($monster, $Mondata);

         $monster['base_stats'] = $stats['base_stats'];
         $monster['equipment_stats'] = $stats['equipment_stats'];
         $monster['total_stats'] = $stats['total_stats'];
         $monster['current_stats'] = $stats['current_stats'];

         $processedMonsters[] = $monster;
      }

      return $processedMonsters;
   }

   private function processEquipment(object $rawDataEquipment)
   {
      $processedEquipment = array();

      foreach ($rawDataEquipment as $data)
      {
         $equipment =
         [
            'equipment_id' => $data->equipment_id,
            'equipment_name' => $data->equipment_name,
            'type' => $data->type,
            'affinity' => ['affinity_id' => $data->affinity_id, 'affinity_name' => $data->affinity_name],
            'ability' => ['ability_name' => $data->ability_name, 'ability_effect' => $data->effect],
            'move_id' => $data->move_id,
            'hp' => $data->boost_hp,
            'mp' => $data->boost_mp,
            'attack' => $data->boost_attack,
            'magic' => $data->boost_magic,
            'defense' => $data->boost_defense,
            'drop_chance' => $data->drop_chance
         ];

         $processedEquipment[] = $equipment;
      }

      return $processedEquipment;
   }

   private function processMove(object $rawDataMove)
   {
      $processedMoves = array();

      foreach ($rawDataMove as $data) 
      {
         $move =
         [
            'move_id' => $data->move_id,
            'move_name' => $data->move_name,
            'type' => $data->type,
            'power' => $data->power,
            'move_effect' => $data->effect,
            'cost' => $data->cost,
            'target' => $data->target,
            'affinity' => ['affinity_id' => $data->affinity_id, 'affinity_name' => $data->affinity_name],
            'source' => isset($data->source) ? $data->source : null,
            'slot' => isset($data->slot) ? $data->slot : null,
         ];

         $processedMoves[] = $move;
      }

      return $processedMoves;
   }

   private function calculateStats(array $entity, object $data)
   {
      $stats =
      [
         'base_stats' =>
         [
            'hp' => 0,
            'mp' => 0,
            'attack' => 0,
            'magic' => 0,
            'defense' => 0
         ],
         'points_stats' =>
         [
            'hp' => 0,
            'mp' => 0,
            'attack' => 0,
            'magic' => 0,
            'defense' => 0
         ],
         'equipment_stats' =>
         [
            'hp' => 0,
            'mp' => 0,
            'attack' => 0,
            'magic' => 0,
            'defense' => 0
         ],
         'total_stats' =>
         [
            'hp' => 0,
            'mp' => 0,
            'attack' => 0,
            'magic' => 0,
            'defense' => 0
         ],
         'current_stats' =>
         [
            'hp' => 0,
            'mp' => 0
         ]
      ];

      foreach (['hp', 'mp', 'attack', 'magic', 'defense'] as $stat) {
         $base = (int) floor($data->{'max_' . $stat} / 100 * $entity['level']);
         $stats['base_stats'][$stat] = $base;

         $equipmentBonus = collect($entity['equipment'])->sum(fn($item) => (int) ($item[$stat] ?? 0));
         $stats['equipment_stats'][$stat] = $equipmentBonus;

         $pointsStat = isset($data->{'stat_points_' . $stat}) ? $data->{'stat_points_' . $stat} : 0;
         $stats['points_stats'][$stat] = $pointsStat;

         $total = $base + $equipmentBonus + $pointsStat;
         $stats['total_stats'][$stat] = $total;
      }

      $stats['current_stats']['hp'] = $stats['total_stats']['hp'];
      $stats['current_stats']['mp'] = $stats['total_stats']['mp'];

      return $stats;
   } 

   public function index(array $offset) // offset has x and y and thies can be 0, 1, -1
   {
      $encounter = session()->get('encounter');

      $phase = $encounter['phase'];
      $turn = $encounter['turn'];
      $currentX  = (int) session()->get('menu_x');
      $currentY  = (int) session()->get('menu_y');
      
      $coordinates = ["x" => $currentX, "y" => $currentY];
      $coordinates["x"] += $offset['x'];
      $coordinates["y"] += $offset['y'];

      // the max size of the menu is a 2*2
      if($coordinates["x"] > 1)
      {$coordinates["x"] = 1;}

      if($coordinates["x"] < 0)
      {$coordinates["x"] = 0;}

      if($coordinates["y"] > 1)
      {$coordinates["y"] = 1;}

      if($coordinates["y"] < 0) 
      {$coordinates["y"] = 0;}

      // make so backspace has offset of -1, -1 and make it so moveslect menu goes to the actions menu
      // and the if on the actions menu try to ge back to the prefes hero

      if($offset['x'] == 0 && $offset['y'] == 0) // 0 x, 0 y means a enter.
      {
         $hero = array_filter($encounter['heroes'], fn($hero) => $hero['selected_hero'] == true)[0];
         $heroKey = $hero['instance_id'];
         $index = $coordinates['y'] * 2 + $coordinates['x'];
         $choosingMove = null;
         $hover = array_pad($this->getHeroOptions($hero), 4, null)[$index];

         if($hover !== null) 
         {
            if (in_array($hero['selected_options'], ['Attack', 'Magic'], true)) 
            {
               $choosingMove = $hover;
               $encounter['heroes'][$heroKey]['selected_move'] = $hover; 
            } 
            elseif(in_array($hover, ['Attack', 'Magic'], true)) 
            {
               $encounter['heroes'][$heroKey]['selected_options'] = $hover;
               $encounter['heroes'][$heroKey]['selected_move'] = null;
            } 
            elseif(in_array($hover, ['Run', 'Skip'], true)) 
            {
               $encounter['heroes'][$heroKey]['selected_options'] = $hover; 
            }

            if(in_array($hover, ['Run', 'Skip'], true) || $choosingMove)
            {
               if(isset($encounter['heroes'][$heroKey + 1]))
               {
                  $encounter['heroes'][$heroKey]['selected_hero'] = false;
                  $encounter['heroes'][$heroKey + 1]['selected_hero'] = true;
               }
               else
               {
                  $phase = 'monster_actions';
                  $turn += 1;
               }
            }
         }

         // $hero['selected_hero'] = true; // if (run or skip is slected) or selected_move has a move. on a hero make the next hero "selected_hero" = true
         // $phase = 'monster_actions'; // if all heroes have had a selected_hero change the $phase to monster_actions
         
      }

      session(
      [
         'encounter' => 
         [
            'heroes' => $encounter['heroes'],
            'monsters' => $encounter['monsters'],
            'turn' => $turn,
            'phase' => $phase,
         ],
      ]);

      session(["menu_x" => $coordinates["x"], "menu_y" => $coordinates["y"]]);

      return $this->displayEncounter($encounter['monsters'], $encounter['heroes'], $coordinates);
   }


}
