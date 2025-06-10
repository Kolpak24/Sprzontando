@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Moje oceny i komentarze</h2>

    @if($ratings->isEmpty())
        <p>Nie masz jeszcze żadnych ocen.</p>
    @else
        <ul class="list-group">
            @foreach($ratings as $rating)
                <li class="list-group-item">
                    <strong>Oferta:</strong> {{ $rating->offer->tytul ?? 'Brak tytułu' }} <br>
                    <strong>Ocena:</strong> {{ $rating->stars }} / 5 <br>
                    <strong>Komentarz:</strong> {{ $rating->comment ?? 'Brak komentarza' }} <br>
                    <small>Od: {{ $rating->ratingFromUser->name ?? 'Anonim' }}, {{ $rating->created_at->format('Y-m-d') }}</small>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection