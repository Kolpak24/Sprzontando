@extends('layouts.app')

@section('content')
@if(isset($oferty))
<table class="table table-bordered table-striped table-hover w-100">
    
    @foreach ($oferty as $offer)
        <tr>
            <td>{{ $offer->id }}</td>
            <td>{{ $offer->lokalizacja}}</td>
           <td>Cena: {{ $offer->cena }} zł</td> 
        </tr>
    @endforeach
</table>
@else
    <p>brak ofert</p>
@endif
@endsection
@section('filtry')

<div class="container">
    <!-- Dropdown z filtrami -->
    <div class="dropdown">
      <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        Filtry 
      </button>
      <div class="dropdown-menu p-4">
        <form>
          <div class="mb-3">
            <label for="sortow" class="form-label">Sortuj:</label>
            <select id="sortow" name="sortuj" class="form-select">
                <option value="data-dsc" {{ request('sortuj') == 'data-dsc' ? 'selected' : '' }}>od najnowszych</option>
                <option value="data-asc" {{ request('sortuj') == 'data-asc' ? 'selected' : '' }}>od najstarszych</option>
                <option value="cena-dsc" {{ request('sortuj') == 'cena-dsc' ? 'selected' : '' }}>od najdroższych</option>
                <option value="cena-asc" {{ request('sortuj') == 'cena-asc' ? 'selected' : '' }}>od najtańszych</option>
            </select>
          </div>
          <div class="mb-3 ">
            <p>Rodzaj sprzątania:</p>
            <div>
                <label for="wszystkie_rodzaje">Wszystkie</label>
                <input type="radio" name="filtr_rodzaj" id="wszystkie_rodzaje" value="wszystkie" {{ request('filtr_rodzaj') == 'wszystkie' ? 'checked' : '' }}>
            </div>
            <div>
                <label for="samochod">Mycie Samochodu</label>
                <input type="radio" name="filtr_rodzaj" id="samochod" value="1" {{ request('filtr_rodzaj') == '1' ? 'checked' : '' }}>
            </div>
            <div>
                <label for="zamiatanie">Zamiatanie</label>
                <input type="radio" name="filtr_rodzaj" id="zamiatanie" value="2" {{ request('filtr_rodzaj') == '2' ? 'checked' : '' }}>
            </div>
            <div>
                <label for="mycie_okien">Mycie okien</label>
                <input type="radio" name="filtr_rodzaj" id="mycie_okien" value="3" {{ request('filtr_rodzaj') == '3' ? 'checked' : '' }}>
            </div>
            <div>
                <label for="lazienki">Mycie Łazienki</label>
                <input type="radio" name="filtr_rodzaj" id="lazienki" value="4" {{ request('filtr_rodzaj') == '4' ? 'checked' : '' }}>
            </div>
            <div>
                <label for="lazienki">Mycie Zębów</label>
                <input type="radio" name="filtr_rodzaj" id="zeby" value="5" {{ request('filtr_rodzaj') == '5' ? 'checked' : '' }}>
            </div>
        </div>
    
        <div class="mb-3">
            <label for="cena-m" class="form-label">Cena min:</label>
            <input type="number" class="form-control" id="cena-m" name="cena_min" value="{{ request('cena_min') }}" placeholder="np. 300">
        </div>
    
        <div class="mb-3">
            <label for="cena-ma" class="form-label">Cena max:</label>
            <input type="number" class="form-control" id="cena-ma" name="cena_max" value="{{ request('cena_max') }}" placeholder="np. 300">
        </div>
        <div class="mb-3">
            <label for="miej" class="form-label">lokalizacja:</label>
            <input type="text" class="form-control" id="miej" name="miejscowosc" value="{{ request('miejscowosc') }}" placeholder="np. Krapkowice">
        </div>
    
        <button type="submit" class="btn btn-success w-100">Zastosuj</button>
        </form>
      </div>
    </div>
  </div>

    @endsection
