@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4">Moje oferty</h1>
    
    <div class="mb-4">
        <nav class="nav nav-pills">
            <a class="nav-link active" href="{{ route('profile.userpanel') }}">Panel główny</a>
            <a class="nav-link" href="{{ route('profile.addofert') }}">Dodaj ofertę</a>
        </nav>
    </div>

    @if($oferty->isEmpty())
        <p>Nie masz jeszcze żadnych ofert - frajer xd.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tytuł</th>
                    <th>Lokalizacja</th>
                    <th>Cena</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                @foreach($oferty as $oferta)
                <tr>
                    <td>{{ $oferta->tytul }}</td>
                    <td>{{ $oferta->lokalizacja }}</td>
                    <td>{{ $oferta->cena }} zł</td>
                    <td>
                        <a href="{{ route('oferty.edit', $oferta->id) }}" class="btn btn-sm btn-warning">Edytuj</a>

                        <form action="{{ route('oferty.destroy', $oferta->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Na pewno usunąć?')">Usuń</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</div>
@endsection
