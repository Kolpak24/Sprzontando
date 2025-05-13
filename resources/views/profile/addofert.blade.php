@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Dodaj nową ofertę</h1>

    {{-- Wyświetlenie błędów walidacji --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Wystąpiły błędy:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formularz dodawania oferty --}}
    <form action="{{ route('profile.addofert') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="tytul" class="form-label">Tytuł oferty</label>
            <input type="text" name="tytul" id="tytul" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="check" class="form-label">Mycie auta</label>
            <input type="checkbox" name="rodzaj[]" id="check" class="sm" value="auto" ><br>

            <label for="check2" class="form-label">zamiatanie</label>
            <input type="checkbox" name="rodzaj[]" id="check2" class="sm" value="zamiatanie" ><br>
            <label for="check3" class="form-label">Mycie okien</label>
            <input type="checkbox" name="rodzaj[]" id="check3" class="sm" value="okna" ><br>

            <label for="check4" class="form-label">Mycie łazienki</label>
            <input type="checkbox" name="rodzaj[]" id="check4" class="sm" value="lazienka" ><br>


            <label for="check5" class="form-label">Mycie zębów</label>
            <input type="checkbox" name="rodzaj[]" id="check5" class="sm" value="zeby" ><br>
        </div>

        <div class="mb-3">
            <label for="opis" class="form-label">Opis</label>
            <textarea name="opis" id="opis" class="form-control" rows="4" required></textarea>
        </div>

        <div class="mb-3">
            <label for="lokalizacja" class="form-label">Lokalizacja</label>
            <input type="text" name="lokalizacja" id="lokalizacja" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="cena" class="form-label">Cena (PLN)</label>
            <input type="number" name="cena" id="cena" class="form-control" step="0.01" min="0" required>
        </div>

        <button type="submit" class="btn btn-success">Dodaj ofertę</button>
    </form>
</div>
@endsection