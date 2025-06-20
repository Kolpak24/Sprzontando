<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Oferty;
use App\Models\Report;
use App\Models\User;
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



    public function banOferta($id)
    {
        $oferta = Oferty::findOrFail($id);
        $oferta->status = 'banned'; // Zmieniamy status oferty na "banned"
        $oferta->save();

        return redirect()->route('adminpanel')->with('success', 'Oferta została zbanowana!');
    }

    public function approveOferta($id)
    {
        $oferta = Oferty::findOrFail($id);
        $oferta->status = 'approved'; // Zmieniamy status oferty na "approved"
        $oferta->save();

        return redirect()->route('adminpanel')->with('success', 'Oferta została zatwierdzona!');
    }


    public function adminpanel()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403); // dostęp tylko dla admina
        }

        // return view('profile.adminpanel');

        $reports = Report::with('oferta')->oldest()->get();

        return view('profile.adminpanel', compact('reports'));
    }

    public function storeReport(Request $request)
    {
        $request->validate([
            'oferta_id' => 'required|exists:oferty,id',
            'powody' => 'nullable|array',
            'opis' => 'nullable|string'
        ]);

        $oferta = Oferty::find($request->oferta_id);

        Report::create([
        'oferta_id' => $request->oferta_id,
        'zglaszajacy_id' => Auth::id(),  // ID aktualnie zalogowanego użytkownika (zgłaszający)
        'zglaszany_id' => $oferta->user_id,  // ID właściciela oferty (zgłaszany)
        'powody' => implode(', ', $request->powody ?? []),
        'opis' => $request->opis
    ]);

    return back()->with('success', 'Zgłoszenie zostało wysłane.');
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
        'rodzaj' => 'array',
        'rodzaj.*' => 'string|max:50',
        'obraz' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // max 2MB
    ]);

    $rodzaj = isset($request->rodzaj) ? implode(', ', $request->rodzaj) : null;

    $imagePath = null;
    if ($request->hasFile('obraz')) {
        $imagePath = $request->file('obraz')->store('zdjecia', 'public');
    }

    Oferty::create([
        'user_id' => auth()->id(),
        'tytul' => $request->tytul,
        'opis' => $request->opis,
        'lokalizacja' => $request->lokalizacja,
        'cena' => $request->cena,
        'rodzaj' => $rodzaj,
        'obraz' => $imagePath, // tu zapisujemy ścieżkę do zdjęcia
    ]);

    return redirect()->route('profile.myoffers')->with('success', 'Oferta została dodana!');
}    
    public function apply($id)
{
    $offer = Oferty::findOrFail($id);
    $userId = auth()->id();

    // pobierz aktualną tablicę applicants lub pustą
    $applicants = $offer->applicants ?? [];

    // sprawdź, czy user już jest na liście
    if (in_array($userId, $applicants)) {
        return back()->with('message', 'Już zgłosiłeś się do tej oferty.');
    }

    // dodaj usera do tablicy
    $applicants[] = $userId;

    // zapisz z powrotem do kolumny jako JSON (dzięki castowi Laravel zrobi to automatycznie)
    $offer->applicants = $applicants;
    $offer->save();

    return back()->with('message', 'Pomyślnie zgłosiłeś się do wykonania zlecenia!');
}



public function destroy($id)
{
    $oferta = Oferty::findOrFail($id);

    // Opcjonalne sprawdzenie uprawnień
    if ($oferta->user_id !== auth()->id()) {
        abort(403, 'Nie masz uprawnień do usunięcia tej oferty.');
    }

    $oferta->delete();

    return redirect()->back()->with('success', 'Oferta została usunięta.');
}
    public function index()
    { 
        $oferty = Oferty::orderBy('created_at', 'desc')->get();

        return view('home', compact('oferty'));

    }
    
    public function myoffer()
    {
        $myoffer = Oferty::where('user_id', Auth::id())->get();
    return view('profile.myoffers', compact('myoffer'));
    }
     public function updateoferty(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:oferty,id',
            'rodzaj' => 'array',
            'rodzaj.*' => 'string|max:50',
            'opis' => 'nullable|string',
            'lokalizacja' => 'required|string|max:255',
            'cena' => 'required|numeric|min:0',
        ]);

        $oferty = Oferty::find($validated['id']);

        // Przykład: zapis kategorii jako string (oddzielonych przecinkami)
        $oferty->rodzaj = implode(',', $validated['rodzaj'] ?? []);
        $oferty->opis = $validated['opis'];
        $oferty->lokalizacja = $validated['lokalizacja'];
        $oferty->cena = $validated['cena'];
        $oferty->save();

        return redirect()->back()->with('success', 'Oferta została zaktualizowana.');
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
        

    } else {
        $query->orderBy('created_at', 'desc');

    }
    if ($request->has('miejscowosc') && $request->input('miejscowosc') !== '') {
        $query->where('lokalizacja', 'like', '%' . $request->input('miejscowosc') . '%');
    }

    if ($request->has('filtr_rodzaj') && $request->input('filtr_rodzaj') !== 'wszystkie') {
        $query->where('rodzaj', 'like', '%' . $request->input('filtr_rodzaj') . '%');;
        // Upewnij się, że rodzaj jest liczbą
    }

    if ($request->has('cena_min') && is_numeric($request->input('cena_min'))) {
        $query->where('cena', '>=', (float) $request->input('cena_min')); // Zamiana na float
    }

    if ($request->has('cena_max') && is_numeric($request->input('cena_max'))) {
        $query->where('cena', '<=', (float) $request->input('cena_max')); // Zamiana na float
    }
    $oferty = $query->get();

    return view('home', compact('oferty'));
}

public function user()
{
    return $this->belongsTo(User::class, 'user_id'); // jeśli kolumna nazywa się inaczej, zmień drugi parametr
}
public function show($id)
{
    $offer = Oferty::with('user')->findOrFail($id);
    return view('oferr', compact('offer'));
    
}

public function cancelReport($id)
{
    $report = Report::findOrFail($id);
    $report->delete();

    return redirect()->back()->with('success', 'Zgłoszenie zostało cofnięte.');
}

public function banUser($userId)
{
    // Zbanuj użytkownika
    $user = User::findOrFail($userId);
    $user->role = 'banned';
    $user->save();

    // Usuń jego ogłoszenia
    Oferty::where('user_id', $userId)->delete();

    return redirect()->back()->with('success', 'Użytkownik został zbanowany, a jego ogłoszenia usunięte.');
}

    public function statystyki()
    {
        // Tu można dodać np. liczbę użytkowników, ofert itp.
        // Przykład: $users = User::count();
        $users = User::withCount('oferta')->get();

        return view('profile.statystyki', compact('users'));
    }

}




