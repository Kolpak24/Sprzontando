@extends('layouts.oferta')

@section('zawartosc')
<div class="container py-5">
    <div class="row justify-content-center align-items-stretch">

        {{-- Lewa kolumna: oferta --}}
        <div class="col-md-8 d-flex flex-column">
            <div class="card shadow-lg rounded mb-4">
                <div class="card-body">
                    <h2 class="card-title">{{ $offer->tytul }}</h2>
                </div>
                @if($offer->obraz)
                    <img src="{{ asset('storage/' . $offer->obraz) }}" class="card-img-bottom" alt="Zdjęcie oferty">
                @else
                    <img src="https://via.placeholder.com/800x400?text=Brak+zdjęcia" class="card-img-bottom" alt="Brak zdjęcia">
                @endif
            </div>

            <div class="card shadow-lg rounded p-3 mb-4">
                <p class="text-muted mb-0">
                    <i class="bi bi-geo-alt"></i> {{ $offer->lokalizacja }} |
                    <i class="bi bi-tag"></i> {{ $offer->rodzaj }}
                </p>
            </div>

            <div class="card shadow-lg rounded p-3 mb-4">
                <h4 class="text-success mb-0">Zapłata: {{ $offer->cena }} zł</h4>
            </div>

            <div class="card shadow-lg rounded p-3 mb-4 flex-grow-1">
                <p class="card-text mb-0">
                    {{ $offer->opis }}
                </p>
            </div>

            <a href="{{ url('/home') }}" class="btn btn-outline-primary mt-auto">
                ← Powrót do ofert
            </a>
        </div>

        {{-- Prawa kolumna: dodatkowe informacje i zgłoszenia --}}
        <div class="col-md-4 d-flex">
            <div class="card shadow-lg rounded p-4 flex-fill">
                <h3 class="mb-3">Dodatkowe informacje</h3>
                <p>
                    Tutaj możesz dodać dowolne dodatkowe informacje, reklamy, kontakty lub inne treści.
                    Możesz też wstawić formularz, mapę lub cokolwiek innego.
                </p>
                <hr>
                <p>Wystawił ogłoszenie: {{ $offer->user->name }}</p>

                @auth
                    @php
                        $userId = auth()->id();
                        // korzystamy z kolekcji $applicants przekazanej z kontrolera (obiekty User)
                        $userApplied = $applicants->pluck('id')->contains($userId);
                        $isOwnOffer = $offer->user_id === $userId;
                    @endphp


                    @if ($isOwnOffer)
                        <div class="alert alert-warning mt-3">
                            Nie możesz zgłosić się do własnej oferty.
                        </div>
                    @elseif (!$userApplied)
                        <form method="POST" action="{{ route('offer.apply', $offer->id) }}" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-primary">Zgłoś się do wykonania zlecenia</button>
                        </form>
                    @else
                        <div class="alert alert-success mt-3">
                            Już zgłosiłeś się do tej oferty.
                        </div>
                    @endif
                @endauth

                <hr>

                <h5 class="mt-3">Zgłoszeni użytkownicy:</h5>
                @if($applicants->isEmpty())
                    <p>Brak zgłoszeń do tej oferty.</p>
                @else
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($applicants as $applicant)
                        <div class="card p-2" style="width: 150px;">
                            <div class="card-body p-2 text-center">
                                <strong>{{ $applicant->name }}</strong>
                                @php
                                    $chosenId = $offer->chosen_user_id;               // id już wybranego (lub null)
                                    $isOwner  = auth()->check() && auth()->id() === $offer->user_id;
                                @endphp

                                @if($chosenId)
                                    {{-- jeżeli oferta ma już wybranego – pokaż badge przy odpowiednim kafelku --}}
                                    @if($chosenId === $applicant->id)
                                        <div class="badge bg-success mt-2">Wybrany</div>
                                    @endif
                                @elseif($isOwner)
                                    {{-- jeżeli nikt jeszcze nie został wybrany i to właściciel – pokaż przycisk --}}
                                    <form method="POST"
                                        action="{{ route('offer.choose', ['offer' => $offer->id, 'user' => $applicant->id]) }}"
                                        class="mt-2">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            Wybierz
                                        </button>
                                    </form>
                                @endif

                            </div>
                        </div>
                    @endforeach
                    </div>
                @endif

            </div>
        </div>

    </div>
</div>
@endsection
