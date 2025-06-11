@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Ranking użytkowników według ocen</h2>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Nr Porządkowy</th>
                <th>Nazwa użytkownika</th>
                <th>Średnia ocena</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $index => $user)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $user->name }}</td>
                    <td>
                        @php
                            $stars = round($user->avg_rating, 1); // średnia zaokrąglona do 1 miejsca po przecinku
                        @endphp

                        <span class="me-2 fw-bold">{{ $stars }} ⭐</span>
                    </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection