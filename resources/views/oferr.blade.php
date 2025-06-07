@extends('layouts.oferta')

@section('zawartosc')
<div class="container py-5">
    <div class="row justify-content-center align-items-stretch"> {{-- flexbox z align-items-stretch --}}

        {{-- Lewa kolumna: oferta --}}
        <div class="col-md-8 d-flex flex-column">

            {{-- Karta ze zdjęciem i tytułem --}}
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

            {{-- Lokalizacja i rodzaj --}}
            <div class="card shadow-lg rounded p-3 mb-4">
                <p class="text-muted mb-0">
                    <i class="bi bi-geo-alt"></i> {{ $offer->lokalizacja }} |
                    <i class="bi bi-tag"></i> {{ $offer->rodzaj }}
                </p>
            </div>

            {{-- Cena --}}
            <div class="card shadow-lg rounded p-3 mb-4">
                <h4 class="text-success mb-0">Zapłata: {{ $offer->cena }} zł</h4>
            </div>

            {{-- Opis --}}
            <div class="card shadow-lg rounded p-3 mb-4 flex-grow-1">
                <p class="card-text mb-0">
                    {{ $offer->opis }}
                </p>
            </div>

            {{-- Przycisk powrotu --}}
            <a href="{{ url('/home') }}" class="btn btn-outline-primary mt-auto">
                ← Powrót do ofert
            </a>

        </div>

        {{-- Prawa kolumna: duża karta (dopasowana wysokość) --}}
        <div class="col-md-4 d-flex">
            <div class="card shadow-lg rounded p-4 flex-fill">
                <h3 class="mb-3">Dodatkowe informacje</h3>
                <p>
                    Tutaj możesz dodać dowolne dodatkowe informacje, reklamy, kontakty lub inne treści.
                    Możesz też wstawić formularz, mapę lub cokolwiek innego.
                </p>
                <hr>
                <p>Zgłosił: {{ $offer->user->name }} </p>
                
            </div>
        </div>

    </div>
</div>
@endsection
