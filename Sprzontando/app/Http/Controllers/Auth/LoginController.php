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


  protected function authenticated(Request $request, $user)
{
    // Jeśli rola to "banned", ale czas bana minął – cofnij bana
    if ($user->role === 'banned' && $user->banned_until && now()->greaterThanOrEqualTo($user->banned_until)) {
        $user->role = 'user';
        $user->banned_until = null;
        $user->save();
    }

    // Jeśli nadal zbanowany – wyloguj i pokaż błąd
    if ($user->role === 'banned' && $user->banned_until && now()->lessThan($user->banned_until)) {
        Auth::logout();
        return redirect('/login')->withErrors([
            'email' => 'Twoje konto jest zbanowane do ' . $user->banned_until->format('d.m.Y H:i') . '.',
        ]);
    }

}
}