<?php

namespace App\Services;

use App\Services\EncounterService;
use App\Services\MenuService;

class MapService
{

   public function loadMap()
   {
      $currentX  = (int) session()->get('map_x');
      $currentY  = (int) session()->get('map_y');
      session(["gameState" => "map"]);

      $map = session()->get("map");
      $coordinates = ["x" => $currentX, "y" => $currentY];

      $map[$coordinates['y']][$coordinates['x']] = 'X';

      foreach ($map as &$mapPart) 
      {$mapPart = str_replace(' ', '&nbsp', $mapPart);}

      $data = ["display" => $map, "coordinates" => $coordinates];

      return response()->json($data);
   }

   static private $solid = ['-', '|', '<', '>', 'S', '$', '#', 'I', 'R'];

   public function index(array $offset)
   {
      $currentX  = (int) session()->get('map_x');
      $currentY  = (int) session()->get('map_y');
      session(["gameState" => "map"]);

      $newX = $offset['x'] + $currentX;
      $newY = $offset['y'] + $currentY;

      $map = session()->get("map");
      $tile = $map[$newY][$newX];

      if (!collect(self::$solid)->contains($tile)) 
      {
         $map[$newY][$newX] = 'X';
         session(["map_x" => $newX, "map_y" => $newY]);
         $coordinates = ["x" => $newX, "y" => $newY];
      } 
      else 
      {
         $map[$currentY][$currentX] = 'X';
         $coordinates = ["x" => $currentX, "y" => $currentY];
      }

      foreach ($map as &$mapPart) 
      {$mapPart = str_replace(' ', '&nbsp', $mapPart);}

      $data = ["display" => $map, "coordinates" => $coordinates];

      $activator =
      [
         '<' => 'activateTransitionPoint',
         '>' => 'activateTransitionPoint',
         'S' => 'activateSavePoint',
         '$' => 'activateShopPoint',
         '#' => 'activateBossEncounterPoint',
         'I' => 'activateInformationPoint',
         'R' => 'activateRecruitmentPoint',
         '.' => 'activateEncounterPoint',
      ];

      if (isset($activator[$tile])) 
      {$data = $this->{$activator[$tile]}();}

      return response()->json($data);
   }

   private function activateEncounterPoint() 
   {
      session(["gameState" => "encounter"]);
      $encounterService = new EncounterService();
      return $encounterService->loadEncounter();
   }

   private function activateInformationPoint()
   {
      session(["gameState" => "menu"]);
      $menuService = new MenuService();
      return $menuService->index();
   }
}