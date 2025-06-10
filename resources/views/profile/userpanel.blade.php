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
@endsection
