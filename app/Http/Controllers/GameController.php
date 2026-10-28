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

      session(["map_x" => $x, "map_y" => $y]);
      session(["menu_x" => 0, "menu_y" => 0]);
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

   public function dispatchInput(Request $request)
   {
      $validated = $request->validate([
         'direction' =>
         [
            'required_if:action,move',
            'in:left,right,up,down,enter,back',
         ],
      ]);

      $offsets =
      [
         'left'  => ['x' => -1, 'y' => 0],
         'right' => ['x' => 1, 'y' => 0],
         'up'    => ['x' => 0, 'y' => -1],
         'down'  => ['x' => 0, 'y' => 1],
         'enter' => ['x' => 0, 'y' => 0],
         'back' => ['x' => -1, 'y' => -1]
      ];

      $offset = $offsets[$validated['direction']];

      $gameState = session()->get('gameState');

      $output = null;

      if($gameState == "map")
      {
         $mapService = new MapService();
         $output = $mapService->handleInput($offset);
      }
      else if($gameState == "encounter")
      {
         $encounterService = new EncounterService();
         $output = $encounterService->handleInput($offset);
      }
      else if($gameState == "menu")
      {
         $menuService = new MenuService();
         $output = $menuService->handleInput($offset);
      }
      
      return $output;
   }
}
