<?php

use App\Http\Controllers\map\MapController;
use App\Http\Controllers\login_register\LoginController;
use App\Http\Controllers\login_register\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('game', [MapController::class, 'getMapGameState'])->middleware('auth')->name('game');
Route::post('player-move', [MapController::class, 'updateMapGameState']);

Route::view('/', 'login');
Route::view('login', 'login')->name('login');
Route::view('register', 'register')->name('register');

Route::post('login', [LoginController::class, "login"])->middleware('throttle:5,1')->name('login.attempt');
Route::post('logout', [LoginController::class, "logout"])->name('logout');
Route::post('register', [RegisterController::class, "register"])->name('register.store');