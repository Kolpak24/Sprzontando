<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Oferty;
use App\Models\Report;
use App\Models\User;
use App\Models\Rating;

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
<<<<<<< Updated upstream
public function show($id)
{
    $offer = Oferty::with('user')->findOrFail($id);
    return view('oferr', compact('offer'));
    
=======

public function show($id)
{
    // Dołączamy relację 'rating', żeby w widoku od razu mieć dostęp do oceny
    $offer = Oferty::with(['user', 'rating'])->findOrFail($id);

    $applicantIds = $offer->applicants ?? [];

    // Pobieramy userów, którzy się zgłosili
    $applicants = User::whereIn('id', $applicantIds)->get();

    return view('oferr', compact('offer', 'applicants'));
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
>>>>>>> Stashed changes
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
}}


<<<<<<< Updated upstream


=======
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
    $query = User::withCount('oferta');

    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
            $q->where('id', $search)
              ->orWhere('name', 'like', "%{$search}%");
        });
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
public function createRating($offerId)
    {
        $oferta = Oferty::with('rating')->findOrFail($offerId);

        // 1) Sprawdzenie: czy zalogowany to właściciel oferty?
        if ($oferta->user_id !== Auth::id()) {
            abort(403, 'Nie masz uprawnień do oceniania tej oferty.');
        }

        // 2) Sprawdzenie: czy w chosen_user_id jest faktycznie ID wykonawcy?
        if (! $oferta->chosen_user_id) {
            abort(400, 'Nie możesz wystawić oceny, bo nie wybrałeś jeszcze wykonawcy.');
        }

        // 3) Sprawdzenie: czy ocena już nie istnieje
        if ($oferta->rating) {
            return redirect()->back()->with('error', 'Ta oferta została już oceniona.');
        }

        // Jeżeli wszystko OK, pokazujemy widok z formularzem:
        return view('ratings.create', compact('oferta'));
    }

    /**
     * Zapisz w bazie nową ocenę dla danej oferty.
     * (analogicznie do RatingController@store)
     */
    public function storeRating(Request $request, $offerId)
    {
        $oferta = Oferty::with('rating')->findOrFail($offerId);

        // 1) Autoryzacja: czy to właściciel oferty?
        if ($oferta->user_id !== Auth::id()) {
            abort(403, 'Nie masz uprawnień do tej akcji.');
        }

        // 2) Czy w chosen_user_id jest ID wykonawcy?
        if (! $oferta->chosen_user_id) {
            abort(400, 'Nie możesz wystawić oceny, bo nie wybrałeś jeszcze wykonawcy.');
        }

        // 3) Czy ocena już istnieje?
        if ($oferta->rating) {
            return redirect()->back()->with('error', 'Ocena już istnieje.');
        }

        // 4) Walidacja pól
        $data = $request->validate([
            'stars'   => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:255',
        ]);

        // 5) Tworzymy nowy rekord w tabeli ratings
        Rating::create([
            'offer_id'             => $oferta->id,
            'rating_from_user_id'  => Auth::id(),
            'rating_to_user_id'    => $oferta->chosen_user_id,
            'stars'                => $data['stars'],
            'comment'              => $data['comment'] ?? null,
        ]);

        return redirect()
            ->route('offer.show', $oferta->id)
            ->with('success', 'Ocena została dodana.');
    }
    public function myRatings()
    {
        $userId = auth()->id();

        // Pobieramy oceny, które zostały wystawione temu użytkownikowi
        $ratings = Rating::where('rating_to_user_id', $userId)
                        ->with('offer', 'ratingFromUser') // jeśli masz relacje zdefiniowane
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('profile.ratings', compact('ratings'));
    }
    
}
>>>>>>> Stashed changes
