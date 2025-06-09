@extends('layouts.app')

@section('content')
@if(isset($reports))


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

    <h2>Tu będą wyświetlane zgłoszone oferty.</h2>


<table class="table table-bordered table-striped table-hover w-100">
    <tr>
        <td>ID</td>
        <td>ID_OFERTY</td>
        <td>ID_ZGLASZAJACEGO</td>
        <td>ID_ZGLASZANEGO</td>
        <td>Powody</td>
        <td>Opis</td>
        <td>Data zgłoszenia</td>
        <td>Akcje</td>
    </tr>

    @foreach ($reports as $report)
        <tr>
            <td>{{ $report->id }}</td>
            <td>{{ $report->oferta_id }}</td>
            <td>{{ $report->zglaszajacy_id }}</td>
            <td>{{ $report->zglaszany_id }}</td>
            <td>{{ $report->powody }}</td>
            <td>{{ $report->opis }}</td>
            <td>{{ $report->created_at }}</td>
            <td>
                <!-- Przycisk Zbanuj -->
                <form action="{{ route('admin.ban', $report->zglaszany_id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    <input type="hidden" name="report_id" value="{{ $report->id }}">
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Zbanować użytkownika i usunąć jego ogłoszenia?')">Zbanuj</button>
                </form>

                <!-- Przycisk Anuluj zgłoszenie -->
                <form action="{{ route('admin.cancelReport', $report->id) }}" method="POST" style="display:inline-block; margin-left: 5px;">
                    @csrf
                    <button class="btn btn-secondary btn-sm" onclick="return confirm('Na pewno usunąć zgłoszenie?')">Cofnij zgloszenie</button>
                </form>
</td>
        </tr>

    @endforeach
</table>
</div>
@else
    <p>Brak zgłoszeń</p>
@endif
@endsection

