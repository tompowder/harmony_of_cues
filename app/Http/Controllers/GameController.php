<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MapService;
use App\Services\EncounterService;
use App\Services\MenuService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
   public function loadGame()
   {
      $user = Auth::user();

      $player = DB::table('players')->where('user_id', '=', $user->id)->get()[0];
      $map = DB::table('maps')->where('id', '=', $player->map_id)->get()[0];

      $mapData = explode("\n", $map->map_data);

      $x = $player->x;
      $y = $player->y;

      session(["x" => $x, "y" => $y]);
      session(["map" => $mapData]);
      session(["player" => $player]);
      session(["gameState" => "map"]);

      $mapData[$y][$x] = "X";

      foreach ($mapData as &$mapPart) {
         $mapPart = str_replace(' ', '&nbsp', $mapPart);
      }

      return view(
         "game",
         [
            "script" => "<script> </script>",
            "display" => $mapData,
            "coordinates" => ["x" => $x, "y" => $y]
         ]
      );
   }

   public function index(Request $request)
   {
      $validated = $request->validate([
         'direction' =>
         [
            'required_if:action,move',
            'in:left,right,up,down',
         ],
      ]);

      $offsets =
      [
         'left'  => [-1, 0],
         'right' => [1, 0],
         'up'    => [0, -1],
         'down'  => [0, 1],
         'enter' => [0, 0]
      ];

      $offset = $offsets[$validated['direction']];

      $gameState = session()->get('gameState');

      $output = null;

      if($gameState == "map")
      {
         $mapService = new MapService();
         $output = $mapService->index($offset);
      }
      else if($gameState == "encounter")
      {
         $encounterService = new EncounterService();
         $output = $encounterService->index($offset);
      }
      else if($gameState == "menu")
      {
         $menuService = new MenuService();
         $output = $menuService->index($offset);
      }
      
      return $output;
   }
}
