<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Oferty;

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

    public function addofert()
    {
        return view('profile.addofert', ['user' => Auth::user()]);
    }

    public function createOferta()
    {
        return view('profile.addofert');
    }
    
    public function storeOferta(Request $request)
    {
        $request->validate([
            'tytul' => 'required|string|max:255',
            'opis' => 'required|string',
            'lokalizacja' => 'required|string|max:255',
            'cena' => 'required|numeric|min:0',
        ]);
    
        Oferty::create([
            'user_id' => auth()->id(),
            'tytul' => $request->tytul,
            'opis' => $request->opis,
            'lokalizacja' => $request->lokalizacja,
            'cena' => $request->cena,
        ]);
    
        return redirect()->route('profile.myoffers')->with('success', 'Oferta została dodana!');
    }

    public function index()
    {
        $oferty = Oferty::all();
        return view('home', compact('oferty'));
    }
    public function filtry(Request $request)
    {
        $query = Oferty::query();
    if ($request->has('sortuj')) {
        $sort = $request->input('sortuj');

        switch ($sort) {
            case 'data-dsc':
                $query->orderBy('created_at', 'desc');
                break;
            case 'data-asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'cena-dsc':
                $query->orderBy('cena', 'desc');
                break;
            case 'cena-asc':
                $query->orderBy('cena', 'asc');
                break;
        }
        
    }
    if ($request->has('miejscowosc') && $request->input('miejscowosc') !== '') {
        $query->where('lokalizacja', 'like', '%' . $request->input('miejscowosc') . '%');
    }

    if ($request->has('filtr_rodzaj') && $request->input('filtr_rodzaj') !== 'wszystkie') {
        $rodzaj = $request->input('filtr_rodzaj');
        // Upewnij się, że rodzaj jest liczbą
        if (in_array($rodzaj, ['1', '2', '3', '4'])) {
            $query->where('rodzaj_sprzatania', (int) $rodzaj); // Zamiana na int
        }
    }

    if ($request->has('cena_min') && is_numeric($request->input('cena_min'))) {
        $query->where('cena', '>=', (float) $request->input('cena_min')); // Zamiana na float
    }

    if ($request->has('cena_max') && is_numeric($request->input('cena_max'))) {
        $query->where('cena', '<=', (float) $request->input('cena_max')); // Zamiana na float
    }
    $oferty = $query->get();

    return view('home', compact('oferty'));
}}



