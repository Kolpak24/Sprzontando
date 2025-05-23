<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SprzontandoController;

Route::redirect('/', '/home');
//Route::get('/', function () {
//    return view('home');
//});

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
    Route::get('/myoffers', [SprzontandoController::class, 'myoffer'])->name('profile.myoffers');
    Route::post('/myoffers', [SprzontandoController::class, 'updateoferty'] )->name('profile.editoffers');
    Route::get('/myworks', [SprzontandoController::class, 'myworks'])->name('profile.myworks');

    Route::get('/addofert', [SprzontandoController::class, 'addofert'])->name('profile.addofert');
    
    Route::get('/addofert', [SprzontandoController::class, 'createOferta'])->name('profile.addofert');
    Route::post('/addofert', [SprzontandoController::class, 'storeOferta'])->name('oferty.store');
    Route::post('/report', [SprzontandoController::class, 'storeReport'])->name('report.store');

    Route::get('/adminpanel', [SprzontandoController::class, 'adminpanel'])->name('adminpanel');

    Route::get('/myoffers/{id}', [SprzontandoController::class, 'destroy'])->name('profile.deleteoffers');
});

//Route::get('/home', [SprzontandoController::class, 'index']);
Route::get('/home', [SprzontandoController::class, 'filtry']);


    Route::put('/adminpanel/ban/{id}', [SprzontandoController::class, 'banOferta'])->name('admin.banOferta');
    Route::put('/adminpanel/approve/{id}', [SprzontandoController::class, 'approveOferta'])->name('admin.approveOferta');

//Route::get('/home', [SprzontandoController::class, 'index']);
Route::get('/home', [SprzontandoController::class, 'filtry']);
