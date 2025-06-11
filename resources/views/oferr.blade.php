@extends('layouts.oferta')

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

    <!-- Scripts & Styles via Vite -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
@push('styles')
<style>
    #star-rating i {
        font-size: 1.8rem;
        color: #ccc;
        cursor: pointer;
        transition: color 0.2s ease-in-out;
    }

    #star-rating i.selected,
    #star-rating i.hovered {
        color: #f4c150;
    }
</style>
@endpush

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
                    <i class="bi bi-geo-alt"></i> {{ $offer->lokalizacja }} &nbsp;|&nbsp;
                    <i class="bi bi-tag"></i> {{ $offer->rodzaj }}
                </p>
            </div>

            <div class="card shadow-lg rounded p-3 mb-4">
                <h4 class="text-success mb-0">Zapłata: {{ $offer->cena }} zł</h4>
            </div>

            <div class="card shadow-lg rounded p-3 mb-4 flex-grow-1">
                <p class="card-text mb-0">{{ $offer->opis }}</p>
            </div>

            <a href="{{ url('/home') }}" class="btn btn-outline-primary mt-auto">← Powrót do ofert</a>
        </div>

        {{-- Prawa kolumna: zgłoszenia i ocena --}}
        <div class="col-md-4 d-flex">
            <div class="card shadow-lg rounded p-4 flex-fill">

                <p><strong>Autor:</strong> {{ $offer->user->name }}</p>

                @auth
                    @php
                        $userId       = auth()->id();
                        $isOwnOffer   = $offer->user_id === $userId;
                        $applicantIds = $offer->applicants ?? [];
                        $applicants   = \App\Models\User::whereIn('id', $applicantIds)->get();
                        $userApplied  = in_array($userId, $applicantIds);
                        $chosenUserId = $offer->chosen_user_id;
                    @endphp

                    {{-- Przycisk zgłoszenia --}}
                    @if(!$isOwnOffer && !$chosenUserId && !$userApplied)
                        <form method="POST" action="{{ route('offer.apply', $offer->id) }}" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm">Zgłoś się do wykonania zlecenia</button>
                        </form>
                    @elseif($userApplied)
                        <div class="alert alert-success mt-3">Już zgłosiłeś się do tej oferty.</div>
                    @endif

                    <hr>

                    {{-- Lista zgłoszonych użytkowników --}}
                    <h5 class="mt-3">Zgłoszeni użytkownicy:</h5>
                    @if($applicants->isEmpty())
                        <p>Brak zgłoszeń do tej oferty.</p>
                    @else
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($applicants as $applicant)
                                <div class="card p-2" style="width: 150px;">
                                    <div class="card-body p-2 text-center">
                                        <strong>{{ $applicant->name }}</strong>
                                        @if($chosenUserId === $applicant->id)
                                            <div class="badge bg-success mt-2">Wybrany</div>
                                        @elseif($isOwnOffer && !$chosenUserId)
                                            <form method="POST" action="{{ route('offer.choose', ['offer' => $offer->id, 'user' => $applicant->id]) }}" class="mt-2">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary">Wybierz</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Sekcja oceny wykonawcy --}}
                    @if($isOwnOffer)
                        <div class="mt-4 p-3 border rounded bg-light">
                            @if($chosenUserId)
                                @php $hasRating = (bool) $offer->rating; @endphp

                                @if(!$hasRating)
                                    {{-- Formularz oceny --}}
                                    <h5 class="mb-3">Wystaw ocenę wykonawcy</h5>
                                    <p class="mb-2">
                                        <strong>Wykonawca:</strong> {{ \App\Models\User::find($chosenUserId)?->name ?? '— brak danych' }}
                                    </p>

                                    <form method="POST" action="{{ route('ratings.store', $offer->id) }}">
                                        @csrf
                                        <input type="hidden" name="offer_id" value="{{ $offer->id }}">
                                        <input type="hidden" name="rating_from_user_id" value="{{ auth()->id() }}">
                                        <input type="hidden" name="rating_to_user_id" value="{{ $offer->chosen_user_id }}">

                                        {{-- Gwiazdki --}}
                                        <div class="mb-3">
                                            <label class="form-label">Ocena (1–5):</label>
                                            <div id="star-rating" class="d-flex gap-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star-fill" data-value="{{ $i }}"></i>
                                                @endfor
                                            </div>
                                            <input type="hidden" name="stars" id="stars" value="{{ old('stars') }}">
                                            @error('stars')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Komentarz --}}
                                        <div class="mb-3">
                                            <label for="comment" class="form-label">Komentarz (max 255 znaków):</label>
                                            <textarea name="comment" id="comment" class="form-control" rows="3" maxlength="255">{{ old('comment') }}</textarea>
                                            <div class="text-end">
                                                <small id="char-count" class="text-muted">Pozostało 255 znaków</small>
                                            </div>
                                            @error('comment')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>


                                        <button type="submit" class="btn btn-success">Zapisz ocenę</button>
                                    </form>


                                @else
                                    {{-- Podgląd oceny --}}
                                    <h5 class="mb-2">Ocena wykonawcy</h5>
                                    <p class="mb-1">
                                        <strong>Wykonawca:</strong> {{ \App\Models\User::find($chosenUserId)?->name ?? '— brak danych' }}
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
                @endauth

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    // ⭐ GWIAZDKI OCENY
    const stars = document.querySelectorAll('#star-rating i');
    const hiddenInput = document.getElementById('stars');
    let selectedValue = parseInt(hiddenInput.value) || 0;

    function updateStars(value) {
        stars.forEach((star, index) => {
            star.classList.toggle('selected', index < value);
        });
    }

    stars.forEach((star, index) => {
        star.addEventListener('click', () => {
            selectedValue = index + 1;
            hiddenInput.value = selectedValue;
            updateStars(selectedValue);
        });

        star.addEventListener('mouseenter', () => {
            stars.forEach((s, i) => {
                s.classList.toggle('hovered', i <= index);
            });
        });

        star.addEventListener('mouseleave', () => {
            stars.forEach((s) => s.classList.remove('hovered'));
        });
    });

    if (selectedValue) {
        updateStars(selectedValue);
    }

    // 💬 LIMIT ZNAKÓW W KOMENTARZU
    const textarea = document.getElementById('comment');
    const counter = document.getElementById('char-count');
    const maxLength = 255;

    if (textarea && counter) {
        textarea.addEventListener('input', function () {
            let remaining = maxLength - textarea.value.length;

            if (remaining < 0) {
                textarea.value = textarea.value.substring(0, maxLength);
                remaining = 0;
            }

            counter.textContent = `Pozostało ${remaining} znaków`;

            // opcjonalnie kolor ostrzegawczy
            if (remaining <= 20) {
                counter.classList.add('text-danger');
            } else {
                counter.classList.remove('text-danger');
            }
        });
    }
});
</script>

@endpush
