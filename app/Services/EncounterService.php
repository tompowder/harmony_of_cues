<?php

namespace App\Services;

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

      $encounters = DB::table('encounters')->where('map_id ', '=', $player->map_id)->get();
      $randomNumber = random_int(0, 100);
      $encounter = null;

      $j = 0;
      for ($i = $randomNumber; $i > 0; )
      {
         $encounter = $encounters[$j++];
         $i -= $encounter->encounter_chance;
      }

      // SELECT id, encounter_id, quantity, FLOOR(RAND() * (max_level - min_level + 1)) + min_level AS random_level FROM `encounter_monsters`

      $monsters = DB::table('encounter_monsters')->where('encounter_id', '=', $encounter->id)->get();


      return;
   }

   public function index()
   {

      return;
   }
}
