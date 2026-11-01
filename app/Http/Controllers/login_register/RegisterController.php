<?php

namespace App\Http\Controllers\login_register;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
   public function register(Request $request)
   {
      $credentials = $request->validate([
         'name' => ['required', 'string'],
         'email' => ['required', 'email'],
         'password' => ['required']
      ]);

      $credentials['password'] = bcrypt($credentials['password']);

      $user = User::create($credentials);

      DB::table('players')->insert([
         'map_id' => $user->id,
         'user_id' => $user->id,
         'gold' => 20,
         'x' => 17,
         'y' => 8,
      ]);

      // DB::table('saved_heroes')->insert(
      // [
      //   'id' => 1,
      //   'player_id' => 1,
      //   'hero_stat_point_id' => 3,
      //   'model_hero_id' => 1,
      //   'level' => 1,
      //   'xp' => 0
      // ]); 

      // DB::table('move_saved_heroes')->insert(
      // [
      //   [
      //    'id' => 1,
      //    'move_id' => 15,
      //    'saved_heroes_id' => 1,
      //    'slot' => 1,
      //    'source' => 'level'
      //   ],
      //   [
      //    'id' => 2,
      //    'move_id' => 18,
      //    'saved_heroes_id' => 1,
      //    'slot' => 2,
      //    'source' => 'level'
      //   ]
      // ]);

      Auth::login($user);

      return redirect()->route('game');
   }
}
