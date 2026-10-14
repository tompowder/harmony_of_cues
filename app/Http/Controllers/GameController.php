<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Game\MapService;

class GameController extends Controller
{
   public function index(MapService $mapService)
   {
      return $mapService->test();
   }
}
