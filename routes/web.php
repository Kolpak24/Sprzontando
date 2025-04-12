<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SprzontandoController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes([
    'verify' => true
]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/profile', [Controller::class, 'edit'])->name('profile.edit')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [SprzontandoController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [SprzontandoController::class, 'update'])->name('profile.update');
});