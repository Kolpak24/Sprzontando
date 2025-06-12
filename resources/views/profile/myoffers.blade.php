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
    <div class="container-fluid">
        <h2>Tutaj będą wyświetlane twoje aktywne oferty.</h2>
    @if(isset($myoffer))
        <table class="table table-bordered table-striped table-hover w-100">
            <tr>

                <th>Obraz</th>
                <th>Tytul</th>
                <th>Rodzaj</th>
                <th>Lokalizacja</th>
                <th>Cena</th>
                <th>Opis</th>
                <th>Akcje</th>
            </tr>
             @foreach ($myoffer as $moffer)
            
        <tr>
            <td onclick="window.location='{{ route('oferr', $moffer->id) }}'" style="cursor:pointer;">
                @if($moffer->obraz)
                    <img src="{{ asset('storage/' . $moffer->obraz) }}" alt="Zdjęcie oferty" style="max-width: 100px; max-height: 70px; object-fit: cover;">
                @else
                    <img src="https://via.placeholder.com/100x70?text=Brak+zdjęcia" alt="Brak zdjęcia">
            @endif
            </td>
            <td onclick="window.location='{{ route('oferr', $moffer->id) }}'" style="cursor:pointer;">{{$moffer->tytul}}</td>
            <td onclick="window.location='{{ route('oferr', $moffer->id) }}'" style="cursor:pointer;">{{ $moffer->rodzaj }}</td>
            <td onclick="window.location='{{ route('oferr', $moffer->id) }}'" style="cursor:pointer;">{{ $moffer->lokalizacja }}</td>
            <td onclick="window.location='{{ route('oferr', $moffer->id) }}'" style="cursor:pointer;">Cena: {{ $moffer->cena }} zł</td>
            <td onclick="window.location='{{ route('oferr', $moffer->id) }}'" style="cursor:pointer;">{{ Str::limit($moffer->opis, 100, '...') }}</p></td>
            <td>
                <!-- Przycisk do otwierania modala -->
                <button class="btn  btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $moffer->id }}">
                    Edytuj
                </button>
                <div class="modal fade" id="editModal{{ $moffer->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $moffer->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('profile.editoffers') }}">
                            @csrf
                            <input type="hidden" name="id" value="{{ $moffer->id }}">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalLabel{{ $moffer->id }}">Edytuj ofertę</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-2">
                                        <label><strong>Zmień kategorie:</strong></label><br>
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

                                    <div class="mb-2">
                                        <label for="opis">Zmień opis</label>
                                        <textarea name="opis" class="form-control" rows="3"> {{ $moffer->opis }}</textarea>
                                    </div> 
                                     <div class="mb-2">
                                    <label for="lokalizacja" class="form-label">Zmień Lokalizacje:</label>
                                    <input type="text" name="lokalizacja" id="lokalizacja" class="form-control" value="{{ $moffer->lokalizacja }}">
                                 </div>
                                    <div class="mb-3">
                                        <label for="cena" class="form-label">Cena (PLN)</label>
                                        <input type="number" name="cena" id="cena" class="form-control" step="0.01" min="0" required value="{{ $moffer->cena }}">
                                    </div>
                                </div>
                                  
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-danger">Potwierdz</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                 
                <form action="{{ route('profile.deleteoffers', $moffer->id) }}" method="get" style="display:inline-block; margin-left: 5px;" onsubmit="return confirm('Na pewno chcesz usunąć tę ofertę?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Usuń</button>
                </form>

                <form action="{{ route('oferta.zakoncz', $moffer->id) }}" method="POST" style="display:inline-block; margin-left: 5px;" onsubmit="return confirm('Czy na pewno to koniec?')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success btn-sm">Zakończ zlecenie</button>
                </form>
            </td> 
        </tr>
                @endforeach

        </table>
        @else
        Brak ofert
        @endif
    </div>
</div>
@endsection