@extends('layouts.app')

@section('content')
@if(isset($reports))

{{-- <div class="container">
    <h1 class="mb-4">Admin Panel</h1>
    <div class="mb-4">
        <nav class="nav nav-pills">
            <a class="nav-link active" href="{{ route('profile.userpanel') }}">Panel główny</a>
        </nav> 
</div>--}}



<div class="container">
    <div class="alert alert-success">
        <h4 class="mb-0">Cześć {{ Auth::user()->name }}! 👋</h4>
        <p>A co to za śliczny administrator tu przyszedł!</p>
    </div>

    <h1>Panel administratora</h1>
    <p>Tu będą wyświetlane zgłoszone oferty.</p>


<table class="table table-bordered table-striped table-hover w-100">
    <tr>
        <td>ID</td>
        <td>ID_OFERTY</td>
        <td>ID_ZGLASZAJACEGO</td>
        <td>ID_ZGLASZANEGO</td>
        <td>Powody</td>
        <td>Opis</td>
        <td>Data zgłoszenia</td>
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
        </tr>

    @endforeach
</table>
</div>
@else
    <p>brak zgłoszeń</p>
@endif
@endsection
=======
    <table class="table table-bordered table-striped table-hover w-100">
        <thead>
            <tr>
                <th>ID</th>
                <th>ID_OFERTY</th>
                <th>ID_ZGLASZAJACEGO</th>
                <th>ID_ZGLASZANEGO</th>
                <th>Powody</th>
                <th>Opis</th>
                <th>Data zgłoszenia</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
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
                        <!-- Form for Ban -->
                    <form action="{{ route('admin.banOferta', $report->oferta_id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-danger">Ban</button>
                    </form>
                    <!-- Przycisk Jest Git -->
                    <form action="{{ route('admin.approveOferta', $report->oferta_id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-success">Jest Git</button>
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

