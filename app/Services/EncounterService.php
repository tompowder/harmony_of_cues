<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class EncounterService
{
   public function loadEncounter()
   {
      $encounterOpsions = ['Attack', 'Magic', 'Run', 'Skip'];
      // -------------    -------------
      // | Monster 1 |    | Monster 2 |
      // | HP 20/20  |    | HP 20/20  |
      // -------------    -------------
      //
      // ---------------  ------------
      // | Hero 1      |  | Hero 2   |
      // | HP 20/20    |  | HP 10/15 |
      // | MP 10/10    |  | MP 20/20 |
      // |*Attack Magic|  ------------
      // | Run    Skip |
      // ---------------

      $player = session()->get('player');

      $encounters = DB::table('encounters')->where('map_id', '=', $player['map_id'])->get();
      $randomNumber = random_int(0, 100);
      $encounter = null;
      $total = count($encounters);

      for ($i = 0; $i < $total; $i++)
      {
         $encounter = $encounters[$i++];
         $randomNumber -= $encounter->encounter_chance;

         if($randomNumber > 0)
         {break;}
      }

      $totalEncounterData = array();

      $monsters = DB::table('encounter_monsters')->select(DB::raw("level_schemas.id as level_id, level_schemas.max_hp, level_schemas.max_mp, level_schemas.max_attack, level_schemas.max_magic, level_schemas.max_defense,
      affinities.id as affinity_id, abilities.name as ability_name, abilities.name as ability_name, abilities.effect, model_monsters.gold_drop, model_monsters.xp_drop, 
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

         $monsterEquipment = DB::table('equipment_monsters')->select(DB::raw("equipment_monsters.drop_chance, equipment.name as equipment_name, equipment.type, equipment.move_id,
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
         moves.power, moves.effect, moves.cost, moves.target, affinities.id as affinity_id, affinities.name as affinity_name"))->
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

         $totalEncounterData[] = $completeMonster;
      }

      $totalheroData = array();

      $heroes = DB::table('saved_heroes')->select(DB::raw("level_schemas.id as level_id, level_schemas.max_hp, level_schemas.max_mp, level_schemas.max_attack, level_schemas.max_magic, level_schemas.max_defense,
      hero_stat_points.stat_points_hp, hero_stat_points.stat_points_mp, hero_stat_points.stat_points_attack, hero_stat_points.stat_points_magic, hero_stat_points.stat_points_defense,
      saved_heroes.level, saved_heroes.xp, saved_heroes.id as hero_id, affinities.id as affinity_id, abilities.name as ability_name, abilities.name as ability_name, abilities.effect, entities.name as hero_name" ))->
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

         $heroEquipment = DB::table('equipment_saved_hero')->select(DB::raw("equipment.name as equipment_name, equipment.type, equipment.move_id,
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
         moves.power, moves.effect, moves.cost, moves.target, affinities.id as affinity_id, affinities.name as affinity_name'))->
         leftJoin('moves', 'move_saved_heroes.move_id', '=', 'moves.id')->
         leftJoin('affinities', 'moves.affinity_id', '=', 'affinities.id')->
         where('saved_heroes_id', '=', $hero->hero_id)->get();

         $totalhero['heroMoves'] = $heroMoves;
         $totalheroData[] = $totalhero;
      }
      
      dd($totalheroData);

      return;
   }

   public function index()
   {

      return;
   }

}
