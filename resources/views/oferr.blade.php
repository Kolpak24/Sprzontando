<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- ✅ Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
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
<<<<<<< Updated upstream
                <p>Zgłosił: {{ $offer->user->name }} </p>
                
=======
                <p>Zgłosił: {{ $offer->user->name }}</p>

                @auth
                    @php
                        $userId = auth()->id();
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
                                        $chosenId = $offer->chosen_user_id;
                                        $isOwner  = auth()->check() && auth()->id() === $offer->user_id;
                                    @endphp

                                    @if($chosenId)
                                        @if($chosenId === $applicant->id)
                                            <div class="badge bg-success mt-2">Wybrany</div>
                                        @endif
                                    @elseif($isOwner)
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

                @php
                    $isOwner   = auth()->check() && auth()->id() === $offer->user_id;
                    $hasWorker = (bool) $offer->chosen_user_id;
                    $hasRating = (bool) $offer->rating;
                @endphp

                @if($isOwner)
                    <div class="mt-4 p-3 border rounded bg-light">
                        @if($hasWorker)
                            @if(!$hasRating)
                                {{-- Formularz oceny --}}
                                <h5 class="mb-3">Wystaw ocenę wykonawcy</h5>
                                <p class="mb-2">
                                    <strong>Wykonawca:</strong>
                                    @php
                                        $wykonawca = \App\Models\User::find($offer->chosen_user_id);
                                    @endphp
                                    {{ $wykonawca?->name ?? '— brak danych' }}
                                </p>

                                <form action="{{ route('ratings.store', $offer->id) }}" method="POST">
                                    @csrf

                                    <div class="mb-3">
                                        <label class="form-label">Ocena (1–5 gwiazdek):</label>
                                        <div id="star-rating" class="d-flex gap-1 fs-4" style="cursor: pointer;">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star-fill text-secondary" data-value="{{ $i }}"></i>
                                            @endfor
                                        </div>
                                        <input type="hidden" name="stars" id="stars" value="{{ old('stars') }}">
                                        @error('stars')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="comment" class="form-label">Komentarz (opcjonalnie):</label>
                                        <textarea name="comment" id="comment"
                                                class="form-control"
                                                rows="3" maxlength="255">{{ old('comment') }}</textarea>
                                        @error('comment')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Max. 255 znaków</div>
                                    </div>

                                    <button type="submit" class="btn btn-success">
                                        Zapisz ocenę
                                    </button>
                                </form>
                            @else
                                {{-- Podgląd oceny --}}
                                <h5 class="mb-2">Ocena wykonawcy</h5>
                                <p class="mb-1">
                                    <strong>Wykonawca:</strong>
                                    @php
                                        $wykonawca = \App\Models\User::find($offer->chosen_user_id);
                                    @endphp
                                    {{ $wykonawca?->name ?? '— brak danych' }}
                                </p>
                                <p class="mb-1">
                                    <strong>Ocena:</strong>
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $offer->rating->stars)
                                            <span style="color: #f4c150; font-size: 1.2rem;">★</span>
                                        @else
                                            <span style="color: #ccc; font-size: 1.2rem;">☆</span>
                                        @endif
                                    @endfor
                                    ({{ $offer->rating->stars }})
                                </p>
                                @if($offer->rating->comment)
                                    <p class="mt-2"><strong>Komentarz:</strong> {{ $offer->rating->comment }}</p>
                                @endif
                            @endif
                        @else
                            <p class="text-muted">Nie możesz wystawić oceny, dopóki nie wybierzesz wykonawcy.</p>
                        @endif
                    </div>
                @endif
>>>>>>> Stashed changes
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const stars = document.querySelectorAll('#star-rating i');
        const hiddenInput = document.getElementById('stars');

        let selectedValue = parseInt(hiddenInput.value) || 0;

        function updateStars(value) {
            stars.forEach((star, index) => {
                if (index < value) {
                    star.classList.remove('text-secondary');
                    star.classList.add('text-warning');
                } else {
                    star.classList.remove('text-warning');
                    star.classList.add('text-secondary');
                }
            });
        }

        stars.forEach((star, index) => {
            star.addEventListener('click', () => {
                selectedValue = index + 1;
                hiddenInput.value = selectedValue;
                updateStars(selectedValue);
            });

            star.addEventListener('mouseenter', () => {
                updateStars(index + 1);
            });

            star.addEventListener('mouseleave', () => {
                updateStars(selectedValue);
            });
        });

        // Inicjalizacja
        if (selectedValue) {
            updateStars(selectedValue);
        }

        console.log("Star rating script loaded"); // ← DODAJ TO TUTAJ
    });
    document.addEventListener('DOMContentLoaded', function () {
        const modal = new bootstrap.Modal(document.getElementById('userDetailsModal'));
        const content = document.getElementById('userDetailsContent');

        document.querySelectorAll('.btn-user-details').forEach(button => {
            button.addEventListener('click', () => {
                const userId = button.getAttribute('data-user-id');
                content.innerHTML = '<p>Ładowanie danych...</p>';
                modal.show();

                fetch(`/user/${userId}/details`)
                    .then(res => {
                        console.log('Response status:', res.status);
                        return res.json();
                    })
                    .then(data => {
                        console.log('Dane z API:', data);
                        // reszta kodu do budowania modala
                    })
                    .catch(err => {
                        console.error('Błąd fetch:', err);
                });

            });
        });
    });
</script>
@endpush
@endsection
