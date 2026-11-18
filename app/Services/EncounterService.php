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
      model_monsters.id as monster_id, model_monsters.boss, model_monsters.ai, entities.name as monster_name, entities.id as entity_id, encounter_monsters.quantity, encounter_monsters.min_level, encounter_monsters.max_level,
      min_level AS random_level"))->
      leftJoin('model_monsters', 'encounter_monsters.model_monster_id', '=', 'model_monsters.id')->
      leftJoin('entities', 'model_monsters.entity_id', '=', 'entities.id')->
      leftJoin('level_schemas', 'entities.level_schema_id', '=', 'level_schemas.id')->
      leftJoin('affinities', 'entities.affinity_id', '=', 'affinities.id')->
      leftJoin('abilities', 'entities.ability_id', '=', 'abilities.id')->
      where('encounter_monsters.encounter_id', '=', $encounter->id)->get(); // $encounter->id
      
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
         moves.id as move_id, moves.power, moves.effect, moves.cost, moves.category, moves.target, affinities.id as affinity_id, affinities.name as affinity_name"))->
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
      saved_heroes.level, saved_heroes.xp, saved_heroes.id as hero_id, affinities.id as affinity_id, affinities.name as affinity_name, abilities.name as ability_name, abilities.name as ability_name, abilities.effect, entities.name as hero_name, entities.id as entity_id" ))->
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
         moves.id as move_id, moves.power, moves.effect, moves.cost, moves.category, moves.target, affinities.id as affinity_id, affinities.name as affinity_name'))->
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
         'phase' => 'hero_turn'
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
            'entity_id' => $Hdata->entity_id,
            'entity_type' => 'hero',
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
            'entity_id' => $Mondata->entity_id,
            'entity_type' => 'monster',
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
            'ai' => json_decode($Mondata->ai, true),
            'equipment' => $this->processEquipment($EData),
            'moves' => $this->processMove($MData),
            'move_preference' => null,
            'selected_action' => null,
            'selected_move' => null,
            'effects' => [],
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
            'category' => $data->category,
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
         $targetes = $this->processTargeting($hero, $encounter, 'heroes');
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
            $encounter['heroes'][$heroKey]['targets'] = $this->processTargeting($hero, $encounter, 'heroes');
            $coordinates = ["x" => 0, "y" => 0];
         }

         if($encounter['phase'] == 'monster_turn')
         {
            $this->encounterPhaseChange($encounter);
         }
      }

      return [$encounter, $coordinates];
   }

   private function processHeroBack(array $encounter, array &$coordinates)
   {
      $hero = array_first(array_filter($encounter['heroes'], fn($hero) => $hero['selected_hero'] == true));
      $heroKey = $hero['instance_id'];
      $targets = $hero['targets'];
      $coordinates = ['x' => 0, 'y' => 0];

      if(in_array($targets['target_mode'], ['single', 'all'], true))
      {
         $encounter['heroes'][$heroKey]['targets']['selectable']['enemies'] = [];
         $encounter['heroes'][$heroKey]['targets']['selectable']['allies'] = [];
         $encounter['heroes'][$heroKey]['targets']['target_mode'] = null;
         $encounter['heroes'][$heroKey]['selected_move'] = null;

         $this->dialogue = $this->MService->processDialogue(['type' => 'encounter' ,'id' => 'select_move'], ['type' => $hero['selected_action']]);
         return $encounter;
      }
      elseif (in_array($hero['selected_action'], ['Attack', 'Magic'], true))
      {
         $encounter['heroes'][$heroKey]['selected_action'] = null;

         $this->dialogue = $this->MService->processDialogue(['type' => 'encounter' ,'id' => 'select_action']);
         return $encounter;
      }

      if($heroKey == 0)
      {return $encounter;}

      for ($lastHeroKey = ($heroKey - 1); $lastHeroKey <= 0; $lastHeroKey--) 
      {
         $lastHero = $encounter['heroes'][$lastHeroKey];

         if ($lastHero['current_stats']['hp'] <= 0) 
         {continue;}
  
         $encounter['heroes'][$lastHeroKey]['selected_hero'] = true;
         $encounter['heroes'][$lastHeroKey]['selected_move'] = null;
         $encounter['heroes'][$lastHeroKey]['selected_action'] = null; 
         $encounter['heroes'][$lastHeroKey]['targets']['selected']['enemies'] = [];
         $encounter['heroes'][$lastHeroKey]['targets']['selected']['allies'] = [];
         
         return $encounter;
      }

   } 
   
   private function processTargeting(array $entity, array $encounter, string $entityType, string $target = '')
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
      
      if($target == '')
      {
         $move = array_filter($entity['moves'], fn($move) => $move['move_id'] === $entity['selected_move']);
         $target = array_first($move)['target'];
      }
      
      $entityKey = $entity['instance_id']; 
      $selectable = $encounter[$entityType][$entityKey]['targets'];

      switch ($target) 
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
         case 'self_and_allies':
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
            $selectableIds = array_map(fn($hero) => $hero['instance_id'], $selectableAllies);

            $selectable['selectable']['allies'] = $selectableIds;
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
            throw new Exception('Unknown move target: ' . $target);
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
  
      $encounter['phase'] = 'monster_turn';
  
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
      $phase = $encounter['phase'];
      
      if($phase == 'monster_turn')
      {
         foreach($encounter['monsters'] as &$monster)
         {
            $monster = $this->AiReader($monster, $encounter);
         }

         $phase = 'resolve_turn';
      }

      if($phase == 'resolve_turn')
      {
         $this->resolveEncounter($encounter);
      }
      
      if($phase == 'hero_turn')
      {
         $encounter['turn']++;
      }

      return $encounter;
   }
   
   // Implement damage calculations.
   // Implement Defense calculations.
   // Implement affinity calculations.
   
   // Implement move effect reader
   // Implement ability effect reader
   // Implement ability events hooks

   private function resolveEncounter(array $encounter)
   {
      // 1. Run attempt
      // Check if any hero has selected Run.
      // If the encounter is a boss encounter, heroes cannot escape.
      // Otherwise, calculate the escape chance.
      // If escape is successful, end the encounter.
      // If escape fails, continue resolving the turn.
      // The remaining hero actions are discarded after a failed escape.

      // 2. Synergy detection and validation
      // Collect the selected moves from all heroes and monsters.
      // Check whether all participants can afford the HP and MP costs of their original moves.
      // Query the synergy data using the selected move IDs.
      // If a synergy is found for the the selected move ID,
      // Check if the participant are using the corect moves to complete the synergy
      // If all requirements are satisfied, register the synergy.
      // Store the synergy move ID, calculation rules, original move IDs,
      // original targets and participant entity IDs.

      // 3. Synergy execution
      // Process registered synergies in the following order: Hero-Monster synergies -> Hero synergies -> Monster synergies.
      // Process the synergy rules using the RuleReader.
      // Before executing each synergy, check whether all participants are alive.
      // If a participant has died, cancel the synergy.
      // If the synergy is cancelled, restore the original moves
      // If the synergy executes successfully, deduct the required costs, 
      // consume the original moves and execute the synergy move.

      // 4. Entity move execution
      // Process heroes first, followed by monsters.
      // Process entities from left to right, as seen on the screen.
      // For each entity:
      // - Retrieve the selected move and target.
      // - Skip the entity if it is dead.
      // - Skip the move if it has already been consumed by a synergy.
      // - Check whether the entity can still use the move.
      // - If the move cannot be used, skip the action.
      // - Otherwise, deduct the HP or MP cost.
      // - Execute the move using the MoveExecuter.

      // 5. End-of-turn processing
      // Prepare the encounter for the next turn.
      // remove selected_action, selected_move, move_preference and clear targets
      // of alle the monster and heroes

   }

   private function MoveExecuter(array $data, array $move, array $encounter, string $entityType)
   {
      // 1. Determine enemies, allies and move type
      // check wat a enemies or allies is
      // Check whether the move is Attack, Magic or Piercing.

      // 2.  Determine whether the move is a synergy move.
      // $data['synergy'] determines whether the move is a synergy.

      // If it is not a synergy:
      // - Retrieve the entity using the move from $data['entity'].

      // If it is a synergy:
      // - Retrieve the synergy move ID.
      // - Retrieve the synergy rules.
      // - Retrieve the original move IDs.
      // - Retrieve the original targets.
      // - Retrieve the participant entity IDs.
      // - Retrieve the participant entities and their current stats.
      //
      // These values are available in $data['synergy_data'].

      // 3. Determine the affected targets.
      // For normal moves, use $data['entity']['targets'].
      // For synergy moves, use the synergy move's target
      // and any additional targeting rules defined in: ['synergy_data']['rules']['actions'].
      
      // Identify each affected entity using its instance ID.
      // Retrieve the targets' relevant stats, including HP, Max HP, Defense and Affinity.

      // 4. Determine how the move should be processed.
      // Check the move's effects for:
      // - Healing effect.
      // - Additional effects that should be applied alongside damage.

      // A move with 0 Power does not perform damage calculations.
      // It only applies its specified effects.

      // 5. Calculate the move's offensive stat.
      // Attack moves use the user's Attack stat.
      // Magic moves use the user's Magic stat.
      // Piercing moves use the highest offensive stat of the user (Attack or Magic).

      // If the move is a synergy move, replace the user's offensive stat
      // with the value calculated using: $data['synergy_data']['rules']['calculation'].
      // The participating entities and their stats are available in: $data['synergy_data']['participants'].

      // 6. Calculate damage for each target.
      // Base Damage = Offensive Stat × Move Power × Affinity Multiplier
      // Determine Effective Defense:
      // - Attack: Target Defense.
      // - Magic: Target Defense × 0.5.
      // - Piercing: 0 Defense.

      // Final Damage = Base Damage - Effective Defense
      // The minimum damage dealt by a damaging move is 1.
      // Do not calculate Affinity Multipliers or Effective Defense for healing moves.

      // 7. Apply the calculated damage or healing to each target.
      // Ensure HP cannot exceed the target's maximum HP.
      // Ensure HP cannot fall below 0.

      // 8. Apply additional move effects.
      // Process any additional effects defined in move.effect.
      // These can include status Stat modifications, Damage reduction.
      // Store the applied effects in entity['effects'] in $encounter.

      // 9. Update the encounter and return the result.
      // Apply all changes to the corresponding entities in $encounter.
      // Ensure all changes are preserved.
      // Return the updated $encounter.
      
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

   }

   private function ruleReader(array $synergyData, array $encounter)
   {
      // 1. Retrieve and decode the synergy rules.
      // Retrieve the rules from $synergyData.
      // Decode the JSON rules into a PHP array.
      //
      // The rules contain:
      // - calculation: Determines how the synergy's stats are calculated.
      // - actions: Contains additional rules that must be processed.

      // 2. Process calculation rules.
      // Retrieve the participating entities and their current stats.
      // Determine which stat should be used.
      // Determine the calculation method.
      
      // Possible calculation methods:
      // - average: Calculate the average stat of all participants.
      // - highest: Use the highest stat among participants.
      // - lowest: Use the lowest stat among participants.
      // - added: Add together the stats of all participants.

      // 3. Process action rules.
      // Retrieve all rules defined in $synergyData['rules']['actions'].
      // Process each action using actionReader().

      // 4. Return the processed synergy rules.
      // Return the calculated stats, selected targets
   }

   private function AiReader(array $monster, array $encounter)
   {
      // check if the monster is a boss.
      // if it is, determine which phase the boss is currently in
      // The boss's current phase determines which moves it can use,
      // and its move_selection type

      $AI = $monster['ai'];

      if($monster['boss'])
      {
         $phases = $AI['phases'];

         foreach ($phases as $phase)
         {
            $conditionLogic = $phase['condition_logic'] ?? 'AND';

            $condition = $this->conditionsReader($phase['conditions'], $encounter, $monster, $conditionLogic);

            if($condition)
            {
               $selectPhase = $phase;
               break;
            }
         }

         if(!isset($selectPhase))
         {throw new Exception('phase not found');}

         $monster['phase'] = $selectPhase['name'];
         $moves = array_filter($monster['moves'], fn($move) => in_array($move['move_id'], $selectPhase['moves']));
         $moveSelection = $selectPhase['move_selection'];
      }
      else
      {
         $moves = $monster['moves'];
         $moveSelection = $AI['move_selection'];
      }

      // get all of the monsters moves 
      // filter wat moves the monster can use hp/mp requerment
      // if the monster has no moves it can use then it will skip its turn

      $usableAttackMoves = array_filter($moves, fn($move) => $move['type'] == 'attack' && $move['cost'] < $monster['current_stats']['hp']);
      $usableMagicMoves = array_filter($moves, fn($move) => $move['type'] == 'magic' && 0 < $monster['current_stats']['mp']);

      $usableMoves = array_merge($usableAttackMoves, $usableMagicMoves);

      if(count($usableMoves) == 0)
      {
         $monster['selected_action'] = 'skip';
         $monster['selected_move'] = null;

         return $monster;
      }

      // first check if ai strategy conditions 
      // a ai strategy can be a wat to target or a move it use
      // if multiple triger take the one with the highest priorty of eath type (move/target)
      // if the 2 are incompatible use the move and pick a target based on the moves type
      // ai strategy has more priorty then ai move_selection

      if(isset($AI['strategies']))
      {
         $strategies = $AI['strategies'];

         foreach ($strategies as $strategy)
         {
            $condition = true;
            
            if(isset($strategy['conditions']))
            {
               $conditionLogic = $strategy['condition_logic'] ?? 'AND';
               $condition = $this->conditionsReader($strategy['conditions'], $encounter, $monster, $conditionLogic);
            }
            if($condition)
            {$checkedStrategies[] = $strategy;}
         }

         if(isset($checkedStrategies))
         {
            $priority = collect($checkedStrategies)->sortBy('priority', SORT_DESC, true)->toArray();

            foreach ($priority as $strategy)
            {
               $actions = $strategy['actions'];

               if(!isset($targetAction))
               {
                  $targetAction = collect($actions)->where('strategy_type', '=', 'target')->first();
               }
               
               if(!isset($movesAction))
               {
                  $movesAction = collect($actions)->where('strategy_type', '=', 'move')->first();
               }
            }

            foreach ([$targetAction, $movesAction] as $action)
            {
               if(isset($action))
               {
                  $monster = $this->actionsReader($action, $monster, $encounter);
               }
            }
         }
         
      }

      // if there is no move ai strategy 
      // pick a move based on the monster's move_selection, move_preference and move category
      // supportive, careful, reckless and random are the move_selection types
      // move type support, defensive, offense and balanced  

      if($monster['selected_move'] == null)
      {$monster['selected_move'] = $this->processMoveSelection($usableMoves, $moveSelection, $monster['move_preference'] ?? '');}

      // get the possible targets for the move  
      // if a target strategy (targets.selected) has selected a valid target, that target is used.
      // otherwise, the AI selects a target based on the move's category.

      $selectedMove = array_first(array_filter($moves, fn($move) => $move['move_id'] == $monster['selected_move'])); 
      $monster['selected_action'] = $selectedMove['type'];

      $moveTarget = $this->processTargeting($monster, $encounter, 'monsters');
      
      if($moveTarget['target_mode'] == 'all')
      {
         $monster['targets']['selected']['enemies'] = $moveTarget['selectable']['enemies'];
         $monster['targets']['selected']['allies'] = $moveTarget['selectable']['allies'];
         $monster['targets']['target_mode'] = $moveTarget['target_mode'];
      }
      else if ($moveTarget['target_mode'] == 'single')
      {
         if($monster['targets']['selectable']['enemies'] == [] && $monster['targets']['selectable']['allies'] == [])
         {
            $count = 
            [
               'enemies' => count($moveTarget['selectable']['enemies']), 
               'allies' => count($moveTarget['selectable']['allies'])
            ];

            if($selectedMove['category'] == 'offense' || $selectedMove['category'] == 'balanced')
            {$singleTarget = 'enemies';}
            else if($selectedMove['category'] == 'support' || $selectedMove['category'] == 'defensive')
            {$singleTarget = 'allies';}
            else
            {throw new Exception('move category not found: ' . $selectedMove['category']);}

            if($count[$singleTarget] == 0)
            {
               $monster['selected_action'] = 'skip';
               $monster['selected_move'] = null;
            }
            else
            {
               $monster['targets']['selected'][$singleTarget][] = $moveTarget['selectable'][$singleTarget][random_int(0, $count[$singleTarget] - 1)];
            }
         }
         else if($monster['targets']['selectable']['enemies'] != [])
         {$monster['targets']['selected']['enemies'] = $monster['targets']['selectable']['enemies'];}
         else if($monster['targets']['selectable']['allies'] != [])
         {$monster['targets']['selected']['allies'] = $monster['targets']['selectable']['allies'];}

         $monster['targets']['target_mode'] = 'single';
      }

      return $monster;
   }

   private function conditionsReader(array $conditions, array $encounter, array $data, string $conditionLogic)
   {
      foreach ($conditions as $condition) 
      {
         $conditionType = $condition['type'];
         switch ($conditionType)
         {
            case "move_type": // Attack, Magic, Piercing
            {
               $result = $data['move_type'] == $condition['move_type'];
               break;
            }
            case "same_type_allies_alive":
            {
               $amount = count(array_filter($data['allies'], fn($ally) => $ally['entity_id'] == $data['entity_id']));
               $result = ($amount >= 2) == $condition['value'];
               break;
            }
            case "stat_threshold": // hp_percent, mp_percent, hp, mp, attack, magic, defense
            {
               $statName = $condition['stat'];

               if(in_array($statName, ['hp_percent', 'mp_percent'])) // percentage stats
               {
                  $statType = explode('_', $statName)[0];
                  $current = $data['current_stats'][$statType];
                  $total = $data['total_stats'][$statType];

                  $stat = $current / $total * 100;
               }
               else // regular stats
               {$stat = $data['total_stats'][$statName];}
               
               $result = $this->compareValues($stat, $condition['operator'], $condition['value']);
               break;
            }
            case "turn_count": // string
            {
               $result = $this->compareValues($encounter['turn'], $condition['operator'], $condition['value']);
               break;
            }
            case "phase": // string
            {
               $result = $data['phase'] == $condition['phase_name'];
               break;
            }
            case "party_level": // int
            {
               $partyLevels = array_map(fn($ally) => $ally['level'], $data['allies']);
               $averagePartyLevel = array_sum($partyLevels) / count($partyLevels);

               $result = $this->compareValues($averagePartyLevel, $condition['operator'], $condition['value']);
               break;
            }
            case "monster_defeat_count": // int
            {
               $defeatedMonster = DB::table('player_defeated_monsters')
               ->where('model_monster_id', '=', $condition['monster_id'])
               ->where('player_id', '=', $data['player_id'])->first();

               $amount = $defeatedMonster->amount ?? 0;

               $result = $this->compareValues($amount, $condition['operator'], $condition['value']);
               break;
            }
            case "gold_amount": // int
            {
               $result = $this->compareValues($data['value'], $condition['operator'], $condition['value']);
               break;
            }
            default:
            {throw new Exception('condition type not found: ' . $conditionType);}
         }

         // AND every condition must be true.
         if ($conditionLogic == "AND" && !$result)
         {return false;}

         // OR one condition must be true.
         if ($conditionLogic == "OR" && $result)
         {return true;}
         
      }

      if($conditionLogic == "AND")
      {return true;}
      if($conditionLogic == "OR")
      {return false;}
   }

   private function actionsReader(array $action, array $monster, array $encounter)
   {
      $actionType = $action['type'];
      switch ($actionType)
      {
         case "stat_target": 
         {
            $targetes = $this->processTargeting($monster, $encounter, 'monsters', $action['target']); //(enemy, ally, self_or_ally, self_or_ally_or_enemy)

            $statName = $action['stat']; // (current_hp, current_mp, hp, mp, attack, magic, defense)
            if(in_array($statName, ['current_hp', 'current_mp']))
            {
               $statType = explode('_', $statName)[1];
               $sortState = 'current_stats';
            }
            else
            {
               $statType = $statName;
               $sortState = 'total_stats';
            }

            $measure = $action['stat_measure']; // (lowest, highest)
            $monsters = array_filter($encounter['monsters'], fn($monster) => in_array($monster['instance_id'], $targetes['selectable']['allies']) && $monster['current_stats']['hp'] > 0);
            $heroes = array_filter($encounter['heroes'], fn($hero) => in_array($hero['instance_id'], $targetes['selectable']['enemies']) && $hero['current_stats']['hp'] > 0);   

            $entities = array_merge($monsters, $heroes);
            $sort = collect($entities)->sortBy($sortState . "." . $statType); 

            if($measure == 'lowest')
            {$targetEntity = $sort->first();}
            else if ($measure == 'highest')
            {$targetEntity = $sort->last();}
            else 
            {throw new Exception('Invalid measure: ' . $measure);}

            $entityType = $targetEntity['entity_type'] == 'monster' ? 'allies' : 'enemies';
            $monster['targets']['selectable'][$entityType][] = $targetEntity['instance_id'];

            break;
         }
         case "move_preference":
         {
            $monster['move_preference'] = $action['value'];
            break;
         }
         case "use_move":
         {
            $move = array_first(array_filter($monster['moves'], fn($move) => $move['move_id'] == $action['move_id']));
            $moveType = $move['type'];

            $moveallowed = false;

            if($moveType == 'attack' )
            {
               if($move['cost'] < $monster['current_stats']['hp'])
               {$moveallowed = true;}
            }
            else if($moveType == 'magic')
            {
               if(0 < $monster['current_stats']['mp'])
               {$moveallowed = true;}
            }
            else 
            {
               $moveallowed = true;
            }

            if($moveallowed)
            {
               $monster['selected_action'] = $moveType;
               $monster['selected_move'] = $move['move_id'];
            }
            
            break;
         }
         
      }

      // if the monster has a selected move and there are selected target(s).
      // check if the target and the move are compatible, if not clear the selectable targets
      if($monster['selected_move'] != null && 
      ($monster['targets']['selectable']['allies'] != [] ||  $monster['targets']['selectable']['enemies'] != []))
      {
         $moveTarget = $this->processTargeting($monster, $encounter, 'monster')['selectable'];
         $actionTarget = $monster['targets']['selectable'];
         
         $monsterId = array_filter($moveTarget['allies'], fn($monster) => in_array($monster, $actionTarget['allies']));
         $heroId = array_filter($moveTarget['enemies'], fn($hero) => in_array($hero, $actionTarget['enemies'])); 

         $entityId = array_merge($monsterId, $heroId);

         if($entityId == [])
         {
            $monster['targets']['selectable']['allies'] = [];
            $monster['targets']['selectable']['enemies'] = [];
         }
      }
      
      return $monster;
   }

   private function processMoveSelection(array $usableMoves, string $moveSelection, string $movePreference)
   {
      if($moveSelection == 'random')
      {return $usableMoves[random_int(0, count($usableMoves) - 1)]['move_id'];}
      else if($moveSelection == 'reckless')
      {$categories = ['offense'];}
      else if($moveSelection == 'careful')
      {$categories = ['defensive', 'support'];}
      else if($moveSelection == 'supportive')
      {$categories = ['support'];}
      else
      {throw new Exception('Invalid move selection: ' . $moveSelection);}
      
      $pMoves = array_filter($usableMoves, fn($move) => $move['type'] == $movePreference);
      $sMoves = array_filter($usableMoves, fn($move) => in_array($move['category'], $categories) || $move['category'] == 'balanced');

      foreach ($usableMoves as &$usableMove) 
      {
         $weight = 1;
         if(in_array($usableMove, $pMoves))
         {$weight += 1;}
         if(in_array($usableMove, $sMoves))
         {$weight += 1;}

         $usableMove['weight'] = $weight;
      }

      $totalWeight = array_sum(array_map(fn($move) => $move['weight'], $usableMoves));
      $randomWeight = random_int(1, $totalWeight);

      foreach ($usableMoves as $move) 
      {
         $randomWeight -= $move['weight'];
         if ($randomWeight <= 0) 
         {return $move['move_id'];}
      }
   }

   private function compareValues(int|float $value1, string $operator, int|float $value2)
   {
      return match ($operator) 
      {
         '==' => $value1 == $value2,
         '!=' => $value1 != $value2,
         '<'  => $value1 < $value2,
         '<=' => $value1 <= $value2,
         '>'  => $value1 > $value2,
         '>=' => $value1 >= $value2,
         default => throw new Exception('Invalid comparison operator: ' . $operator),
      };
   }

   
}
