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
         'map_id' => 1,
         'user_id' => $user->id,
         'gold' => 20,
         'x' => 17,
         'y' => 8,
      ]);

      Auth::login($user);

      return redirect()->route('game');
   }
}
