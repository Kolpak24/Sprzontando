<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckIfBanned
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->isCurrentlyBanned()) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['email' => 'Twoje konto zostało zbanowane.']);
        }

        return $next($request);
    }
}
