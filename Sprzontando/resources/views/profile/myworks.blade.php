@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4">Moje zgłoszenia pracy</h1>
    
    <div class="mb-4">
        <nav class="nav nav-pills">
            <a class="nav-link active" href="{{ route('profile.userpanel') }}">Panel główny</a>
            <a class="nav-link" href="{{ url('/home') }}">Znajdz ogłoszenie</a>
        </nav>

    </div>
</div>

<div class="container my-5">
    <h2 class="mb-4">🔧 Oferty, do których się zgłosiłeś</h2>
    @if($appliedOffers->count())
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach ($appliedOffers as $offer)
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $offer->tytul }}</h5>
                            <p class="card-text">{{ Str::limit($offer->opis, 100) }}</p>
                            <a href="{{ route('oferr', $offer->id) }}" class="btn btn-outline-primary">Zobacz szczegóły</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p>Nie zgłosiłeś się do żadnych ofert.</p>
    @endif

    <h2 class="mt-5 mb-4">✅ Oferty, do których zostałeś przyjęty</h2>
    @if($selectedOffers->count())
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach ($selectedOffers as $offer)
                <div class="col">
                    <div class="card border-success">
                        <div class="card-body">
                            <h5 class="card-title">{{ $offer->tytul }}</h5>
                            <p class="card-text">{{ Str::limit($offer->opis, 100) }}</p>
                            <a href="{{ route('oferr', $offer->id) }}" class="btn btn-success">Zobacz szczegóły</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p>Nie zostałeś jeszcze wybrany do żadnej oferty.</p>
    @endif
</div>

@endsection