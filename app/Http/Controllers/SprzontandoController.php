<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SprzontandoController extends Controller
{
    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profil zaktualizowany!');
    }

    public function userpanel()
    {
        return view('profile.userpanel', ['user' => Auth::user()]);
    }

    public function myoffers()
    {
        return view('profile.myoffers', ['user' => Auth::user()]);
    }

    public function myworks()
    {
        return view('profile.myworks', ['user' => Auth::user()]);
    }
}

