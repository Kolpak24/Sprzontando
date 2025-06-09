@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Admin Panel</h1>
    <div class="mb-4">
        <nav class="nav nav-pills">
            <a class="nav-link" href="{{ route('adminpanel') }}">Zgłoszone oferty</a>
            <a class="nav-link active" href="{{ route('statystyki') }}">Statystyki</a>
        </nav> 
    </div>

    <div class="alert alert-success">
        <h4 class="mb-0">Cześć {{ Auth::user()->name }}! 👋</h4>
        <p>A co to za śliczny administrator tu przyszedł!</p>
    </div>

<<<<<<< HEAD
    <h2>Tutaj sa statystyki użytkowników.</h2>
=======
    <h2 class="mb-3">Tutaj są statystyki użytkowników.</h2>
>>>>>>> 8735cf6d5db2a2374e9d6048339229b00c374caa

    <form method="GET" action="{{ route('statystyki') }}" class="mb-3 d-flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control w-25" placeholder="Szukaj po ID lub nazwie">
        <button type="submit" class="btn btn-primary">Szukaj</button>
    </form>

    <table class="table table-bordered table-striped table-hover w-100">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nazwa</th>
                <th>Rola</th>
                <th>Utworzono</th>
<<<<<<< HEAD
                <th>Ile ogłoszeń</th>
                <th>Ocena</th>
=======
>>>>>>> 8735cf6d5db2a2374e9d6048339229b00c374caa
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->role }}</td>
                    <td>{{ $user->created_at }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Brak użytkowników do wyświetlenia</td>
                </tr>
            @endforelse
        </tbody>
    </table>
<<<<<<< HEAD

=======
>>>>>>> 8735cf6d5db2a2374e9d6048339229b00c374caa
</div>
@endsection
