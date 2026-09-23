<?php

use App\Http\Controllers\map\MapController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MapController::class, 'loadMap']);

Route::post('/player-move', [MapController::class, 'playerMove']);