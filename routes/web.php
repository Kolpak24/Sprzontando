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
    Route::get('/offer/{id}', [SprzontandoController::class, 'show'])->name('oferr');

    Route::get('/statystyki', [SprzontandoController::class, 'statystyki'])->name('statystyki');
});

//Route::get('/home', [SprzontandoController::class, 'index']);
Route::get('/home', [SprzontandoController::class, 'filtry']);



Route::post('/ban-user/{userId}', [SprzontandoController::class, 'banUser'])->name('admin.ban');
Route::post('/admin/cancel-report/{id}', [SprzontandoController::class, 'cancelReport'])->name('admin.cancelReport');


//Route::get('/home', [SprzontandoController::class, 'index']);
Route::get('/home', [SprzontandoController::class, 'filtry']);

Route::post('/offers/{id}/apply', [SprzontandoController::class, 'apply'])->name('offer.apply');

Route::middleware('auth')->group(function () {
    // ... inne trasy chronione

    // Trasa do wyboru wykonawcy
    Route::post('/offer/{offer}/choose/{user}', [SprzontandoController::class, 'chooseApplicant'])
        ->name('offer.choose');
});
