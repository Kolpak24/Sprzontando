@extends('layouts.app')

@section('content')
<<<<<<< Updated upstream
=======
@if(isset($oferty))
<table class="table table-bordered table-striped table-hover w-100">
    <tr>
        <th>Obraz</th>
        <th>Tytul</th>
        <th>Rodzaj</th>
        <th>Lokalizacja</th>
        <th>Wynagrodzenie</th>
        <th>Opis</th>
        <th>Akcje</th>
    </tr>
    @foreach ($oferty as $offer)

        <tr data-bs-toggle="modal" data-bs-target="#ofertaModal{{ $offer->id }}" style="cursor: pointer;">
            <td>{{ $offer->obraz}}</td>
            <td>{{ $offer->tytul }}</td>
            <td>{{ $offer->rodzaj }}</td>
            <td>{{ $offer->lokalizacja }}</td>
            <td>Zapłata: {{ $offer->cena}} zł</td>
            <td><p>{{ Str::limit($offer->opis, 100, '...') }}</p></td>
            <td>
                <!-- Przycisk do otwierania modala -->
                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#reportModal{{ $offer->id }}">
                    Report
                </button>
                                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#ofertaModal{{ $offer->id }}">
                    Zgłoś się!
                </button>

               

                <!-- Modal zgłoszenia -->
                <div class="modal fade" id="reportModal{{ $offer->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $offer->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('report.store') }}">
                            @csrf
                            <input type="hidden" name="oferta_id" value="{{ $offer->id }}">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalLabel{{ $offer->id }}">Zgłoś ofertę</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-2">
                                        <label><strong>Powody zgłoszenia:</strong></label><br>
                                        <div><input type="checkbox" name="powody[]" value="Za duzo $"> Pojebalo kogos ostro z cena</div>
                                        <div><input type="checkbox" name="powody[]" value="Wytyczne"> Narusza wytyczne</div>
                                        <div><input type="checkbox" name="powody[]" value="Oszustwo"> Oszustwo</div>
                                    </div>
                                    <div class="mb-2">
                                        <label for="opis">Dodatkowe informacje</label>
                                        <textarea name="opis" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-danger">Wyślij zgłoszenie</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </td>

        </tr>
         <div class="modal fade" id="ofertaModal{{ $offer->id }}" tabindex="-1" aria-labelledby="ofertaModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ofertaModalLabel">{{$offer->tytul}}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zamknij"></button>
                        </div>
                <div class="modal-body">
                    <div class="">

                    </div>
                    <p id="modalOpis">{{$offer->opis}}</p>
                </div>
                <div class="modal-footer">
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#reportModal{{ $offer->id }}">
                    Report
                </button>
                                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#ofertaModal{{ $offer->id }}">
                    Zgłoś się!
                </button>
                
                </div>
                </div>
            </div>
            </div>


        </tr></div>

        </tr>


    @endforeach
</table>
@else
    <p>brak ofert</p>
@endif
@endsection
@section('filtry')
>>>>>>> Stashed changes

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Tutaj cos') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('Jestes zalogowany!') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
