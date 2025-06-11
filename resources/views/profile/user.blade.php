@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Profil użytkownika</h1>

    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{ $user->name }}</h4>
            <p class="card-text"><strong>Email:</strong> {{ $user->email }}</p>
            <p class="card-text"><strong>Rola:</strong> {{ $user->role }}</p>

            {{-- Możesz dodać więcej info jeśli chcesz --}}
        </div>
    </div>

    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">Wróć</a>
</div>
@endsection