@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4">Moje zgłoszenia pracy</h1>
    
    <div class="mb-4">
        <nav class="nav nav-pills">
            <a class="nav-link active" href="{{ route('profile.userpanel') }}">Panel główny</a>
            <a class="nav-link" href="{{ route('home') }}">Znajdz zgłoszenie</a>
        </nav>

    </div>
</div>
@endsection