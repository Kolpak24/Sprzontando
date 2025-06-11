<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
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
       // return view('profile.userpanel', ['user' => Auth::user()]);

        $oferty = Oferty::where('user_id', Auth::id())->get();

        return view('profile.userpanel', ['user' => Auth::user()], compact('oferty'));
    }

    public function myoffers()
    {
        return view('profile.myoffers', ['user' => Auth::user()]);
    }

    public function myworks()
{
    $user = Auth::user();

    // Oferty, do których się zgłosił
    $appliedOffers = Oferty::whereJsonContains('applicants', $user->id)->get();

    // Oferty, w których został wybrany
    $selectedOffers = Oferty::where('chosen_user_id', $user->id)->get();

    return view('profile.myworks', compact('appliedOffers', 'selectedOffers'));
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
        $oferty = Oferty::where('status', '=', 'active')
                ->orderBy('created_at', 'desc')
                ->get();
        return view('home', compact('oferty'));

    }
    
    public function myoffer()
    {
        $myoffer = Oferty::where('user_id', Auth::id())
                 ->where('status', '=', 'active')
                 ->get();
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
        $query = Oferty::where('status', '!=', 'deleted');
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
        // Dokładamy relację 'rating' do wczytania razem z 'user'
        $offer = Oferty::with(['user', 'rating'])->findOrFail($id);
        $user = auth()->user()->load('completedOffers');
        $applicantIds = $offer->applicants ?? [];
        $applicants   = User::whereIn('id', $applicantIds)->get();

        return view('oferr', compact('offer', 'applicants'));
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



    public function chooseApplicant($offerId, $userId)
    {
        // 1) Znajdź ofertę
        $offer = Oferty::findOrFail($offerId);

        // 2) Sprawdź, czy aktualnie zalogowany user jest właścicielem oferty
        if ($offer->user_id !== auth()->id()) {
            abort(403, 'Nie masz uprawnień, aby wybrać wykonawcę do tej oferty.');
        }

        // 3) Pobierz tablicę ID zgłoszonych (JSON)
        $applicantIds = $offer->applicants ?? [];

        // 4) Sprawdź, czy podany $userId jest wśród zgłoszonych
        if (!in_array($userId, $applicantIds)) {
            return back()->with('error', 'Ten użytkownik nie zgłosił się do tej oferty.');
        }

        // 5) Zapisz do kolumny chosen_user_id
        $offer->chosen_user_id = $userId;
        $offer->save();

        return back()->with('success', 'Pomyślnie wybrano wykonawcę zlecenia.');
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


    public function softDeleteOffer($id)
    {
    $offer = Oferty::find($id);

    if (!$offer) {
        return redirect()->route('adminpanel')->with('error', 'Oferta nie została znaleziona.');
    }

    $offer->status = 'deleted';
    $offer->save();

    return redirect()->route('adminpanel')->with('success', 'Oferta została oznaczona jako usunięta.');
    }


public function statystyki(Request $request)
{
    if (Auth::user()->role !== 'admin') {
        abort(403); // dostęp tylko dla admina
    }
    
    $query = User::query();

    if ($search = $request->input('search')) {
        $query->where('name', 'like', "%$search%")
              ->orWhere('id', $search);
    }

    // Pobierz użytkowników z ocenami i liczbą wykonanych zleceń
    $query->withAvg('receivedRatings', 'stars')
          ->withCount('completedOffers');

    // Sortowanie po ocenie (średniej gwiazdek)
    $sort = $request->input('sort_rating');

    if ($sort === 'asc') {
        $query->orderBy('received_ratings_avg_stars', 'asc');
    } elseif ($sort === 'desc') {
        $query->orderBy('received_ratings_avg_stars', 'desc');
    }


    $users = $query->get();

    return view('profile.statystyki', compact('users'));
}
    public function closeRequest($id)
    {
        $report = Report::find($id);

    
        if (!$report) {
            return redirect()->route('adminpanel')->with('error', 'Oferta nie znaleziona.');
        }

        $report->delete();


        return redirect()->route('adminpanel')->with('success', 'Oferta została usunięta.');
    }
    public function createRating(Oferty $offer)
    {
        return view('ratings.create', compact('offer'));
    }

    public function storeRating(Request $request)
    {
        \Log::info('Request data:', $request->all());

        $validated = $request->validate([
            'offer_id'             => 'required|exists:oferty,id',
            'rating_from_user_id'  => 'required|exists:users,id',
            'rating_to_user_id'    => 'required|exists:users,id',
            'stars'                => 'required|integer|min:1|max:5',
            'comment'              => 'nullable|string|max:255',
        ]);

        \Log::info('Validated data:', $validated);

        $offer = Oferty::findOrFail($validated['offer_id']);

        if ($offer->user_id !== (int) $validated['rating_from_user_id']) {
            return redirect()->back()->withErrors('Nie możesz ocenić tej oferty – nie jesteś jej właścicielem.');
        }

        if (Rating::where('offer_id', $offer->id)->exists()) {
            return redirect()->back()->withErrors('Ta oferta została już oceniona.');
        }

        Rating::create($validated);

        return redirect()->back()->with('success', 'Ocena została pomyślnie dodana.');
    }

    public function zakoncz($id)
{
    $oferta = Oferty::findOrFail($id);

    // (Opcjonalnie) Sprawdź, czy aktualny użytkownik jest właścicielem oferty
    if (auth()->id() !== $oferta->user_id) {
        abort(403, 'Brak dostępu do tej oferty');
    }

    $oferta->status = 'zakonczona';
    $oferta->save();

    return redirect()->back()->with('success', 'Zlecenie zostało zakończone.');
}

public function tempBanUser(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'days' => 'required|integer|min:1|max:30',
    ]);

    $user = User::findOrFail($request->user_id);
    $user->role = 'banned';
    $user->banned_until = now()->addDays((int) $request->days);
    $user->save();

    return back()->with('success', "Użytkownik został zbanowany na {$request->days} dni.");
}

public function ranking()
    {
        $users = User::with(['ratings'])
            ->select('users.id', 'users.name', DB::raw('COALESCE(AVG(ratings.stars), 0) as avg_rating'))
            ->leftJoin('ratings', 'users.id', '=', 'ratings.rating_to_user_id')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('avg_rating')
            ->get();

        return view('profile.ranking', compact('users'));
    }

    public function unbanUser(Request $request)
{
    $user = User::findOrFail($request->user_id);

    $user->role = 'user';
    $user->banned_until = null;
    $user->save();

    return redirect()->back()->with('success', 'Użytkownik został odbanowany.');
}
public function showUser($user_id)
{
    $user = User::findOrFail($user_id);
    return view('profile.userinfo', compact('user'));
}
public function ratingFromUser()
{
    return $this->belongsTo(User::class, 'rating_from_user_id');
}

}



