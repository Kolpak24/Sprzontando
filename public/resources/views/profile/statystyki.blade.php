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
<table class="table table-bordered table-striped table-hover w-100">
    <tr>
        <td>ID</td>
        <td>Nazwa</td>
        <td>Rola</td>
        <td>Utworzono</td>
    </tr>

    @foreach ($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->role }}</td>
            <td>{{ $user->created_at }}</td>
    @endforeach  
</div>
@endsection

