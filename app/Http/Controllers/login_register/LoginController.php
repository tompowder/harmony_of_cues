<?php

namespace App\Http\Controllers\login_register;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
   public function login(Request $request): RedirectResponse
   {
      $credentials = $request->validate([
         'email' => ['required', 'email'],
         'password' => ['required'],
      ]);

      if (Auth::attempt($credentials)) {
         $request->session()->regenerate();

         return redirect()->intended('game');
      }

      return back()->withErrors([
         'email' => 'The provided credentials do not match our records.',
      ])->onlyInput('email');
   }

   public function logout(Request $request)
   {
      Auth::logout();

      $request->session()->invalidate();
      $request->session()->regenerateToken();

      return redirect('/');
   }
}
