<?php

namespace App\Services;

use Dom\Entity;
use App\Services\MapService;
use Exception;
use Illuminate\Support\Facades\DB;

class EncounterService
{
   private MenuService $MService;
   private string $dialogue;

   function __construct() 
   {
      $this->MService = new MenuService();
   }

   public function loadEncounter()
   {
      $player = session()->get('player');

      $encounters = DB::table('encounters')->where('map_id', '=', $player['map_id'])->get();
      $roll = random_int(1, 100);
      $chance = 0;
      $encounter = null;

      foreach ($encounters as $candidate) 
      {
         $chance += (int) $candidate->encounter_chance;

         if ($roll <= $chance) 
         {
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
      $monsterNames = implode(', ', array_map(fn(array $monster) => $monster['monster_name'], $monsters));

      $dialogue = $this->MService->processDialogue(['type' => 'encounter' ,'id' => 'encounter_start'], ['monster' => $monsterNames]);

      $encounter = 
      [
         'heroes' => $heroes,
         'monsters' => $monsters,
         'turn' => 1,
         'phase' => 'hero_actions'
      ];

      $encounter = $this->selectNextHero($encounter, -1);

      session([
         'encounter' => $encounter,
         'dialogue' => $dialogue
      ]);

      return $this->displayEncounter($encounter['monsters'], $encounter['heroes'], $dialogue);
   }

   private function displayEncounter(array $monsters, array $heroes, string $dialogue, array $selected = ['x' => 0, 'y' => 0])
   {
      // output exsampel
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

      $menuService = $this->MService;
      $targetMarkers = $this->getTargetMarkers($heroes, $selected);

      $monsterBoxes = array_map(fn(array $monster) => $menuService->createBox(
      [
         (in_array($monster['instance_id'], $targetMarkers['enemies']) ? '*' : ' ') . "{$monster['monster_name']} ",
         " HP {$monster['current_stats']['hp']}/{$monster['total_stats']['hp']} ",
      ]), $monsters);
      
      $heroBoxes = [];

      foreach ($heroes as $hero) 
      {
         $mark = in_array($hero['instance_id'], $targetMarkers['allies']) ? '*' : ' ';

         $box =
         [
            $mark . "{$hero['hero_name']} ",
            " HP {$hero['current_stats']['hp']}/{$hero['total_stats']['hp']} ",
            " MP {$hero['current_stats']['mp']}/{$hero['total_stats']['mp']} "
         ];

         if($hero['selected_hero'] && !$hero['selected_move'])
         {
            $options = $this->formatHeroOptions($hero, $selected);

            $box = array_merge($box, $options);
         }

         $heroBoxes[] = $menuService->createBox($box);
      }

      $lines = $menuService->createBox([$dialogue]);
      $dialogueBox = array_map(fn(string $line) => str_replace(' ', '&nbsp', $line), $lines);
      
      $alleBoxes = array_merge($menuService->combineBoxes($monsterBoxes), $menuService->combineBoxes($heroBoxes), $dialogueBox);

      return ["display" => $alleBoxes, "coordinates" => $selected];
   }

   private function getHeroOptions(array $hero)
   {
      $selectedOption = $hero['selected_action'] ?? null;
      $selectedMove = $hero['selected_move'] ?? null;

      if (in_array($selectedOption, ['Attack', 'Magic'], true) && $selectedMove === null) 
      {
         $moveType = strtolower($hero['selected_action']);
         $moves = array_filter($hero['moves'], fn($move) => $move['type'] === $moveType);

         return array_column($moves, 'move_name', 'move_id');;
      } 

      return [1 => 'Attack', 2 => 'Magic', 3 => 'Run', 4 => 'Skip'];
   }

   private function formatHeroOptions(array $hero, array $selected)
   {
      $options = $this->getHeroOptions($hero);

      if (!in_array('Attack', $options, true) && !in_array('Magic', $options, true)) 
      {
         $moveType = strtolower($hero['selected_action']);

         $moves = array_filter($hero['moves'], fn($move) => $move['type'] === $moveType);
         $options = array_map(fn($move) => $move['move_name']. " " . $move['cost'] . ($move['type'] == 'magic' ? 'mp': 'hp'), $moves);
      }
      else
      {
         $options = array_values($options);
      }

      // the menu uses two columns and two rows
      $options = array_map(fn (string $option) => ' ' . $option, $options);
      $options = array_slice($options, 0, 4);
      $options = array_pad($options, 4, '');
      $options = array_chunk($options, 2);

      $options[$selected['y']][$selected['x']][0] = '*';  

      $box = 
      [
         "{$options[0][0]}{$options[0][1]} ",
         "{$options[1][0]}{$options[1][1]} "
      ];  
        
      return $box;
   }

   private function getTargetMarkers(array $heroes, array $selected)
   {
      $hero = array_filter($heroes, fn($hero) => $hero['selected_hero'] == true);
      $targets = array_first($hero)['targets'];
      $selectable = $targets['selectable'];

      if ($targets['target_mode'] === null)
      {return ['enemies' => [], 'allies' => []];}

      if ($targets['target_mode'] === 'all')
      {return $selectable;}
      
      if($targets['target_mode'] === 'single')
      {$selectableTargets['enemies'] = [$selectable['enemies'][$selected['x']]];}

      $targetGroup = $selected['y'] === 0 ? 'enemies' : 'allies'; 
      $targetId = $selectable[$targetGroup][$selected['x']];

      if($targetGroup == 'enemies')
      {
         return ['enemies' => [$targetId], 'allies' => []];
      }
      elseif($targetGroup == 'allies')
      {
         return ['enemies' => [], 'allies' => [$targetId]];
      }
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
            'current_stats' => [],
            'affinity' => ['affinity_id' => $Hdata->affinity_id, 'affinity_name' => $Hdata->affinity_name],
            'ability' => ['ability_name' => $Hdata->ability_name, 'ability_effect' => $Hdata->effect],
            'equipment' => $this->processEquipment($EData),
            'moves' => $this->processMove($MData),
            'selected_hero' => false,
            'selected_action' => null,
            'selected_move' => null,
            'targets' => 
            [
               'selectable' => ['allies' => [], 'enemies' => []], 
               'selected' => ['allies' => [], 'enemies' => []], 
               'target_mode' => null
            ]
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
            'moves' => $this->processMove($MData),
            'selected_move' => null,
            'targets' => 
            [
               'selectable' => ['allies' => [], 'enemies' => []], 
               'selected' => ['allies' => [], 'enemies' => []], 
               'target_mode' => null
            ]
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

      foreach (['hp', 'mp', 'attack', 'magic', 'defense'] as $stat) 
      {
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

   public function handleInput(array $offset) // offset has x and y and thies can be 0, 1, -1
   {
      [$encounter, $coordinates] = $this->getEncounterState($offset);
      
      [$encounter, $coordinates] = $this->processEncounterInput($encounter, $coordinates, $offset);

      session(
      [
         'menu_x' => $coordinates["x"],
         'menu_y' => $coordinates["y"],
         'dialogue' => $this->dialogue,
         'encounter' => 
         [
            'heroes' => $encounter['heroes'],
            'monsters' => $encounter['monsters'],
            'turn' => $encounter['turn'],
            'phase' => $encounter['phase'],
         ]
      ]);

      return $this->displayEncounter($encounter['monsters'], $encounter['heroes'], $this->dialogue, $coordinates);
   }

   private function getEncounterState(array $offset)
   {
      $encounter = session()->get('encounter');
      $this->dialogue = session()->get('dialogue');
      
      $hero = array_first(array_filter($encounter['heroes'], fn($hero) => $hero['selected_hero'] == true));
      
      // get the dimensions of the heroes options menu
      if($hero['selected_move'] !== null) // gets the dimensions for the target menu
      {
         $targetes = $this->processTargeting($hero, 'heroes', $encounter);
         $selectableTargets = $targetes['selectable'];

         $options = [$selectableTargets['enemies'], $selectableTargets['allies']];

         if($targetes['target_mode'] == 'all')
         {$options = [[null],[null]];}
         elseif($targetes['target_mode'] == 'single')
         {
            $max = max(count($options[0]), count($options[1]));
            $options = array_pad($options, 2, null);
            $options = array_map(fn (array $row) => array_pad($row, $max, null), $options); 
         }
      }
      else // gets the dimensions for the move/action menu
      {
         $options = array_values($this->getHeroOptions($hero));
         $options = $options === [] ? [null, null, null] : $options; // if the hero does not have that type of move
         $options = array_chunk($options, 2);
         $options = array_map(fn (array $row) => array_pad($row, 2, null), $options); 
      }
      
      $coordinates = $this->MService->clampCoordinates($options, $offset);

      return [$encounter, $coordinates];
   }

   private function processEncounterInput(array $encounter, array $coordinates, array $offset)
   {
      // make so backspace has offset of -1, -1 and make it so moveslect menu goes to the actions menu
      // and the if on the actions menu try to ge back to the prefes hero
      if($offset['x'] == -1 && $offset['y'] == -1)
      {
         $encounter = $this->processHeroBack($encounter, $coordinates);
      }

      // 0 x, 0 y means a enter and slect wat the player is hovering over
      elseif($offset['x'] == 0 && $offset['y'] == 0) 
      {
         $encounter = $this->processHeroEnter($encounter, $coordinates);
         
         $hero = array_first(array_filter($encounter['heroes'], fn($hero) => $hero['selected_hero'] == true));
         $heroKey = $hero['instance_id'];

         if(in_array($hero['selected_action'], ['Run', 'Skip'], true) ||  // if the hero has select slected a action/target
           (count($hero['targets']['selected']['enemies']) > 0 || count($hero['targets']['selected']['allies']) > 0)) 
         {
            $encounter = $this->selectNextHero($encounter, $heroKey);
            $coordinates = ['x' => 0, 'y' => 0];
         }
         elseif($hero['selected_move'] !== null) 
         {
            $encounter['heroes'][$heroKey]['targets'] = $this->processTargeting($hero, 'heroes', $encounter);
            $coordinates = ["x" => 0, "y" => 0];
         }

         if($encounter['phase'] == 'monster_actions')
         {
            $this->encounterPhaseChange($encounter);
         }
      }

      return [$encounter, $coordinates];
   }

   private function processHeroBack(array $encounter, array $coordinates)
   {
      dd('test');

      return $encounter;
   }
   
   private function processTargeting(array $entity, string $entityType, array $encounter)
   {
      // 'targets' => 
      // [
      //    'selectable' => ['allies' => [], 'enemies' => []], wat the move can hit
      //    'selected' => ['allies' => [], 'enemies' => []], wat te move will hit
      //    'target_mode' => 'single'/'all' if it will hit all of the targets or one
      // ]

      if($entityType == 'heroes')
      {
         $allies = $encounter['heroes'];
         $enemies = $encounter['monsters'];
         $allieIds = array_map(fn($entity) => $entity['instance_id'], $allies);
         $enemieIds = array_map(fn($entity) => $entity['instance_id'], $enemies);
      }
      elseif($entityType == 'monsters')
      {
         $allies = $encounter['monsters'];
         $enemies = $encounter['heroes'];
         $allieIds = array_map(fn($entity) => $entity['instance_id'], $allies);
         $enemieIds = array_map(fn($entity) => $entity['instance_id'], $enemies);
      }
      else
      {throw new Exception('entity type must be heroes or monsters');}
      
      $move = array_filter($entity['moves'], fn($move) => $move['move_id'] === $entity['selected_move']);
      $moveTarget = array_first($move)['target'];
      $entityKey = $entity['instance_id']; 

      $selectable = $encounter[$entityType][$entityKey]['targets'];

      switch ($moveTarget) 
      {
         case 'self':
         {
            $selectable['selectable']['allies'] = [$entityKey];
            $selectable['selectable']['enemies'] = [];
            $selectable['target_mode'] = 'single';
            break;
         }
         case 'ally':
         {
            $selectableAllies = array_filter($allies, fn($ally) => $ally['instance_id'] != $entityKey);
            $selectableIds = array_map(fn($hero) => $hero['instance_id'], $selectableAllies);
            
            $selectable['selectable']['allies'] = $selectableIds;
            $selectable['target_mode'] = 'single';
            break;
         }
         case 'enemy':
         {
            $selectable['selectable']['enemies'] = $enemieIds;
            $selectable['target_mode'] = 'single';
            break;
         }
         case 'allies':
         {
            $selectableAllies = array_filter($allies, fn($ally) => $ally['instance_id'] != $entityKey);
            $selectableIds = array_map(fn($hero) => $hero['instance_id'], $selectableAllies);

            $selectable['selectable']['allies'] = $selectableIds;
            $selectable['target_mode'] = 'all';
            break;
         }
         case 'self_or_ally':
         {
            $selectable['selectable']['allies'] = $allieIds;
            $selectable['target_mode'] = 'single';
            break;
         }
         case 'self_and_ally':
         {
            $selectable['selectable']['allies'] = $allieIds;
            $selectable['target_mode'] = 'all';
            break;
         }
         case 'self_or_ally_or_enemy':
         {
            $selectable['selectable']['allies'] = $allieIds;
            $selectable['selectable']['enemies'] = $enemieIds;
            $selectable['target_mode'] = 'single';
            break;
         }
         case 'enemies':
         {
            $selectable['selectable']['enemies'] = $enemieIds;
            $selectable['target_mode'] = 'all';
            break;
         }
         case 'all_except_self':
         {
            $selectableAllies = array_filter($allies, fn($ally) => $ally['instance_id'] != $entityKey);

            $selectable['selectable']['allies'] = $selectableAllies;
            $selectable['selectable']['enemies'] = $enemieIds;
            $selectable['target_mode'] = 'all';
            break;
         }
         case 'all':
         {
            $selectable['selectable']['allies'] = $allieIds;
            $selectable['selectable']['enemies'] = $enemieIds;
            $selectable['target_mode'] = 'all';
            break;
         }
         default:
         {
            throw new Exception('Unknown move target: {$moveTarget}');
         }
      }

      return $selectable;
   }

   private function selectNextHero(array $encounter, int $currentHeroKey)
   {
      if($currentHeroKey >= 0)
      {$encounter['heroes'][$currentHeroKey]['selected_hero'] = false;}
      
      for ($heroKey = ($currentHeroKey + 1); $heroKey < count($encounter['heroes']); $heroKey++ ) 
      {
         $hero = $encounter['heroes'][$heroKey];

         // dead heroes automatically skip their turn.
         if ($hero['current_stats']['hp'] <= 0) 
         {
            $encounter['heroes'][$heroKey]['selected_action'] = 'Skip';
            $encounter['heroes'][$heroKey]['selected_move'] = null;
            $encounter['heroes'][$heroKey]['selected_hero'] = false;

            continue;
         }
  
         $encounter['heroes'][$heroKey]['selected_hero'] = true;
         
         return $encounter;
      }
  
      $encounter['phase'] = 'monster_actions';
      $encounter['turn']++;
  
      return $encounter;
  }

   private function processHeroEnter(array $encounter, array &$coordinates) 
   {
      $hero = array_first(array_filter($encounter['heroes'], fn($hero) => $hero['selected_hero'] == true));
      $heroKey = $hero['instance_id'];
      $targets = $hero['targets'];
      
      // target menu
      if(in_array($targets['target_mode'], ['single', 'all'], true)) // if a move is slected
      {
         if($targets['target_mode'] === 'single') // if the move only targets one
         {
            $selectedTarget = [$targets['selectable']['enemies'], $targets['selectable']['allies']];
            $targetId = $selectedTarget[$coordinates['y']][$coordinates['x']]; // choose one entity

            if($coordinates['y'] == 0) // monsters
            {
               $encounter['heroes'][$heroKey]['targets']['selected']['enemies'] = [$targetId];
            }
            if($coordinates['y'] == 1) // heroes
            {
               $encounter['heroes'][$heroKey]['targets']['selected']['allies'] = [$targetId];
            }
            
         }
         else // if move is multi target confirm the entitie group
         {
            $encounter['heroes'][$heroKey]['targets']['selected']['enemies'] = $targets['selectable']['enemies'];
            $encounter['heroes'][$heroKey]['targets']['selected']['allies'] = $targets['selectable']['allies'];
         }

         return $encounter;
      }

      $options = $this->getHeroOptions($hero);
      $index = $coordinates['y'] * 2 + $coordinates['x'];
      $hoverKey = array_keys($options)[$index] ?? null;
      $hoverValue = array_values($options)[$index] ?? null;
      // Attack, Magic, Run, Skip are actions 
      // options are wat the hero can slect now and they can be actions/moves

      if($hoverKey === null || $hoverValue === null) 
      {
         $coordinates = ['x' => 0, 'y' => 0];
         return $encounter;
      }

      // move menu
      if (in_array($hero['selected_action'], ['Attack', 'Magic'], true)) // if attack or magic action is alredy selected
      {
         $move = array_filter($hero['moves'], fn($move) => $move['move_id'] == $hoverKey);
         $move = array_first($move);
         $cost = $move['cost'];
         $usable = false;

         // check if the hero can use the move
         if($hero['selected_action'] == 'Attack')
         {
            if($cost < $hero['current_stats']['hp'])
            {$usable = true;}
            else 
            {
               $this->dialogue = $this->MService->processDialogue(['type' => 'encounter' ,'id' => 'insufficient'], ['type' => 'HP' ,'move' => $move['move_name']]);
            }
         }
         else if($hero['selected_action'] == 'Magic')
         {
            if($hero['current_stats']['mp'] > 0)
            {$usable = true;}
            else
            {
               $this->dialogue = $this->MService->processDialogue(['type' => 'encounter' ,'id' => 'insufficient'],  ['type' => 'MP' ,'move' => $move['move_name']]);
            }
            
         }

         if($usable)
         {
            $encounter['heroes'][$heroKey]['selected_move'] = $hoverKey; // select the move that is hovered
            $this->dialogue = $this->MService->processDialogue(['type' => 'encounter' ,'id' => 'select_target'], ['move' => $move['move_name']]);
         }

         return $encounter;
      } 
      // action menu
      elseif(in_array($hoverValue, ['Attack', 'Magic', 'Run', 'Skip'], true)) // if no action is slected selected
      {
         $encounter['heroes'][$heroKey]['selected_action'] = $hoverValue; // select the action that is hovered
         $encounter['heroes'][$heroKey]['selected_move'] = null;
         $coordinates = ['x' => 0, 'y' => 0];

         if($hoverValue == 'Run')
         {
            $encounter['heroes'] = array_map(fn (array $hero) => [...$hero, 'selected_action' => 'Run'], $encounter['heroes']);
         }

         if(in_array($hoverValue, ['Attack', 'Magic'], true))
         {
            $this->dialogue = $this->MService->processDialogue(['type' => 'encounter' ,'id' => 'select_move'], ['type' => $hoverValue]);
         }

         return $encounter;
      }

      throw new Exception('not a valid state');
   }

   private function encounterPhaseChange(array $encounter)
   {
      $encounter['monsters'] = $this->processMonsterAi($encounter['monsters']);
   }

   private function processMonsterAi(array $monsters)
   {
      dd($monsters);
      foreach($monsters as &$monster)
      {
         $ai = json_decode($monster['ai'], true);

         
      }

      return $monsters;
   }
}
