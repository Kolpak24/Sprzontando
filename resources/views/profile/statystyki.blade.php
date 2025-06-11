@extends('layouts.app')

@section('content')

<div class="container">

    <h1 class="mb-4">Admin Panel</h1>
    <div class="mb-4">
        <nav class="nav nav-pills">
            <a class="nav-link" href="{{ route('adminpanel' ) }}">Zgłoszone oferty</a>
            <a class="nav-link" href="{{ route('statystyki') }}">Statystyki</a>
        </nav> 
</div>





<div class="container">
    <div class="alert alert-success">
        <h4 class="mb-0">Cześć {{ Auth::user()->name }}! 👋</h4>
        <p>A co to za śliczny administrator tu przyszedł!</p>
    </div>


    <h2>Tutaj sa statystyki użytkowników.</h2>


<form method="GET" action="{{ route('statystyki') }}" class="mb-3 d-flex gap-2 align-items-center">
    <input type="text" name="search" value="{{ request('search') }}" class="form-control w-25" placeholder="Szukaj po ID lub nazwie">

    <select name="sort_rating" class="form-select w-auto">
        <option value="">Sortuj po ocenie</option>
        <option value="asc" {{ request('sort_rating') == 'asc' ? 'selected' : '' }}>Od najniższej</option>
        <option value="desc" {{ request('sort_rating') == 'desc' ? 'selected' : '' }}>Od najwyższej</option>
    </select>

    <button type="submit" class="btn btn-primary">Szukaj</button>
</form>

    <table class="table table-bordered table-striped table-hover w-100">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nazwa</th>
                <th>Rola</th>
                <th>Utworzono</th>
                <th>Ile wykonanych zleceń</th>
                <th>
    <a href="{{ route('statystyki', array_merge(request()->all(), [
        'sort_rating' => request('sort_rating') === 'asc' ? 'desc' : 'asc'
    ])) }}" style="text-decoration: none; color: inherit;">
        Ocena
        @if(request('sort_rating') === 'asc')
            &#9650; {{-- strzałka w górę --}}
        @elseif(request('sort_rating') === 'desc')
            &#9660; {{-- strzałka w dół --}}
        @endif
    </a>
</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->role }}</td>
                    <td>{{ $user->created_at }}</td>
                    <td>{{ $user->completed_offers_count }}</td>
                    <td>
                        @if ($user->received_ratings_avg_stars)
                            {{ number_format($user->received_ratings_avg_stars, 2) }} ⭐
                        @else
                            Brak ocen
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('tempBanUser') }}" method="POST" class="d-inline-flex align-items-center gap-2">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <input type="number" name="days" min="1" max="30" class="form-control form-control-sm" placeholder="dni" style="width: 70px;" required>
                            <button type="submit" class="btn btn-warning btn-sm">Temp-ban</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Brak użytkowników do wyświetlenia</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

