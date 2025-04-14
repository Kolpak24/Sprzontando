<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SprzontandoController;

Route::get('/', function () {
    return view('home');
});

Auth::routes([
    'verify' => true
]);
Route::middleware('verified')->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});

Route::get('/profile', [Controller::class, 'edit'])->name('profile.edit')->middleware('auth');

Route::middleware('auth', 'verified')->group(function () {

    Route::get('/profile', [SprzontandoController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [SprzontandoController::class, 'update'])->name('profile.update');

    Route::get('/userpanel', [SprzontandoController::class, 'userpanel'])->name('profile.userpanel');

    Route::get('/myoffers', [SprzontandoController::class, 'myoffers'])->name('profile.myoffers');

    Route::get('/myworks', [SprzontandoController::class, 'myworks'])->name('profile.myworks');

    Route::get('/addofert', [SprzontandoController::class, 'addofert'])->name('profile.addofert');
    
    Route::get('/addofert', [SprzontandoController::class, 'createOferta'])->name('profile.addofert');
    Route::post('/addofert', [SprzontandoController::class, 'storeOferta'])->name('oferty.store');
});