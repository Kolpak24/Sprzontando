@extends('layouts.app')

@section('content')

<div class="container">

    <h1 class="mb-4">Panel użytkownika {{ $user->name}}</h1>
    
    <!-- Menu użytkownika -->
    <div class="mb-4">
        <nav class="nav nav-pills">
            <a class="nav-link active" href="{{ route('profile.userpanel') }}">Panel główny</a>
            <a class="nav-link" href="{{ route('profile.myoffers') }}">Moje oferty</a>
            <a class="nav-link" href="{{ route('profile.myworks') }}">Moje zgłoszenia</a>
            <a class="nav-link" href="{{ route('profile.ratings') }}">Zobacz swoje oceny i komentarze</a>
        </nav>
    </div>
</div>

<div class="container">

    <h2>Tutaj bedzie wyświetlana historia twoich ofert.</h2>
<table class="table table-bordered table-striped table-hover w-100">
    <tr>
        <td>ID</td>
        <td>Tytul</td>
        <td>Rodzaj</td>
        <td>Lokalizacja</td>
        <td>Cena</td>
        <td>Opis</td>
        <td>Status oferty</td>
        <td>Data zgłoszenia</td>
    </tr>

    @foreach ($oferty as $ofert)
        <tr>
            <td>{{ $ofert->id }}</td>
            <td>{{ $ofert->tytul }}</td>
            <td>{{ $ofert->rodzaj }}</td>
            <td>{{ $ofert->lokalizacja }}</td>
            <td>{{ $ofert->cena }}</td>
            <td>{{ $ofert->opis }}</td>
            <td>{{ $ofert->status }}</td>
            <td>{{ $ofert->created_at }}</td>
        </tr>

    @endforeach
</table>
</div>
</div>
@endsection
