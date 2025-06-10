<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request; // ← TO JEST KLUCZOWE!
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

  protected function authenticated(Request $request, $user){
      if ($user->role === 'banned') {Auth::logout();
          return redirect('/login')->withErrors(['email' => 'Twoje konto zostało zbanowane.']);}

        return redirect()->intended($this->redirectTo);
    }
}