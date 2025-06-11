@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card shadow-lg rounded p-5">
        <h2 class="mb-3">
            👤 {{ $user->name }} {{ $user->surname ?? '' }}
        </h2>

        {{-- Średnia ocena --}}
        @php
            $avgRating = $user->ratings()->avg('stars');
        @endphp

        <p class="mb-4">
            <strong>⭐ Średnia ocena:</strong>
            @if($avgRating)
                {{ number_format($avgRating, 2) }} / 5
                <span class="text-warning ms-2">
                    @for ($i = 1; $i <= 5; $i++)
                        {!! $i <= round($avgRating) ? '★' : '☆' !!}
                    @endfor
                </span>
            @else
                <span class="text-muted">Brak ocen</span>
            @endif
        </p>

        {{-- Ostatnie zlecenia --}}
        <h4 class="mb-3">📝 Ostatnie zlecenia:</h4>
        @if ($user->completedOffers->isEmpty())
            <p class="text-muted">Brak zakończonych zleceń</p>
        @else
            <ul class="list-group mb-4">
                @foreach ($user->completedOffers->take(5) as $offer)
                    <li class="list-group-item">
                        <strong>{{ $offer->tytul }}</strong>
                        <div class="text-muted">
                            {{ \Illuminate\Support\Str::limit($offer->opis, 50) }}
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif

        {{-- Komentarze --}}
        <h4 class="mb-3">💬 Komentarze:</h4>
        @if ($user->ratings->isEmpty())
            <p class="text-muted">Brak komentarzy</p>
        @else
            <div class="list-group mb-4">
                @foreach ($user->ratings as $rating)
                    <div class="list-group-item">
                        <div>
                            <strong>Ocena:</strong>
                            <span class="text-warning ms-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    {!! $i <= $rating->stars ? '★' : '☆' !!}
                                @endfor
                            </span>
                        </div>
                        @if ($rating->comment)
                            <blockquote class="blockquote mt-2 mb-1">
                                <p class="mb-0">"{{ $rating->comment }}"</p>
                            </blockquote>
                        @endif
                        <small class="text-muted">🧑 Od: {{ $rating->ratingFromUser->name ?? 'Anonim' }}</small>
                    </div>
                @endforeach
            </div>
        @endif

        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
            ← Wróć
        </a>
    </div>
</div>
@endsection
