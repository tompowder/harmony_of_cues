<?php

namespace App\Http\Controllers\map;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class MapController extends Controller
{
   static private $solid = ['-', '|', '<', '>', 'S', '$', '#', 'I', 'R'];

   public function createMapGameState() 
   {
      
   }

   public function getMapGameState()
   {
      $map = DB::table('maps')->where('id', '=', '1')->get();

      $mapData = explode("\n", $map[0]->map_data);

      $x = 17;
      $y = 8;

      session(["x" => $x, "y" => $y]);
      session(["map" => $mapData]);
      $mapData[$y][$x] = "X";

      foreach ($mapData as &$mapPart) {
         $mapPart = str_replace(' ', '&nbsp', $mapPart);
      }

      return view(
         "game",
         [
            "script" => "<script> </script>",
            "map" => $mapData,
            "coordinates" => ["x" => $x, "y" => $y]
         ]
      );
   }

   public function updateMapGameState()
   {
      $map = session()->get("map");

      $currentX  = (int) session()->get('x');
      $currentY  = (int) session()->get('y');

      $newX  = (int) request('x');
      $newY  = (int) request('y');

      $tile = $map[$newY][$newX];

      if (!collect(self::$solid)->contains($tile)) {
         $map[$newY][$newX] = 'X';
         session(["x" => $newX, "y" => $newY]);

         $coordinates = ["x" => $newX, "y" => $newY];
      } else {
         $map[$currentY][$currentX] = 'X';

         $coordinates = ["x" => $currentX, "y" => $currentY];
      }

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

      if (isset($activator[$tile])) {
         $this->{$activator[$tile]}();
      }

      foreach ($map as &$mapPart) {
         $mapPart = str_replace(' ', '&nbsp', $mapPart);
      }

      $data = ["map" => $map, "coordinates" => $coordinates];

      return response()->json($data);
   }

   private function activateEncounterPoint() {}
}