<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\OuvrierController;
use \App\Http\Controllers\DomaineController;
use \App\Http\Controllers\MetierController;
use \App\Http\Controllers\RegionController;
use \App\Http\Controllers\DepartementController;
use \App\Http\Controllers\DiplomeController;

$idRegex = '[0-9a-zA-Z\-]+';

Route::get('/', function () {
    return view('admin.admin');
})->name('accueil');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('admin', function () {
    return view('admin.admin');
})->middleware(['auth'])->name('admin');

Route::middleware('auth')->group(function () {
    Route::resource('region', RegionController::class);
    Route::resource('departement', DepartementController::class);
    Route::resource('domaine', DomaineController::class);
    Route::resource('metier', MetierController::class);
    Route::get('ouvriers/rechercher', [OuvrierController::class, 'rechercher'])->name('ouvrier.rechercher');
    Route::get('ouvriers/filtered', [OuvrierController::class, 'filtered'])->name('ouvrier.filtered');
    Route::get('mon_compte', [OuvrierController::class,'get_mon_compte'])->name('ouvrier.mon_compte');
    Route::get('/api/metier', [OuvrierController::class, 'metiersByDomaine']);
    Route::get('/api/departement', [OuvrierController::class, 'departementsByRegion']);
    Route::resource('ouvrier', OuvrierController::class)->except(['index', 'show']);
    Route::resource('diplome', DiplomeController::class);
    
    });
    Route::get('ouvrier', [OuvrierController::class, 'index'])->name('ouvrier.index');
    Route::get('ouvrier/{id}', [OuvrierController::class, 'show'])->name("ouvrier.show");

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
