<?php

namespace App\Http\Controllers\map;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MapController extends Controller
{
   static private $solid = ['-', '|', '<', '>', 'S', '$', '#', 'I', 'R'];


   public function getMapGameState()
   {
      $user = Auth::user();

      $player = DB::table('players')->where('user_id', '=', $user->id)->get()[0];

      $map = DB::table('maps')->where('id', '=', $player->map_id)->get();

      $mapData = explode("\n", $map[0]->map_data);

      $x = $player->x;
      $y = $player->y;

      session(["x" => $x, "y" => $y]);
      session(["map" => $mapData]);
      session(["player" => $player]);

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

   public function updateMapGameState(Request $request)
   {
      $map = session()->get("map");

      $validated = $request->validate([
         'direction' =>
         [
            'required_if:action,move',
            'in:left,right,up,down',
         ],
      ]);

      $currentX  = (int) session()->get('x');
      $currentY  = (int) session()->get('y');

      $offsets =
         [
            'left'  => [-1, 0],
            'right' => [1, 0],
            'up'    => [0, -1],
            'down'  => [0, 1],
         ];

      $offset = $offsets[$validated['direction']];

      $newX = $offset[0] + $currentX;
      $newY = $offset[1] + $currentY;

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