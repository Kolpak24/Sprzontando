@extends('layouts.app')

@section('content')
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
        <p>Co za ładny administrator tu przyszedł!</p>
    </div>

    <h1>Panel administratora</h1>
    <p>Tu będą wyświetlane zgłoszone oferty.</p>
</div>

@endsection