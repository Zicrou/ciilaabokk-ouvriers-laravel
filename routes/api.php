<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OuvrierController;
use App\Http\Controllers\Api\OuvrierLocationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function (){

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');

    Route::middleware('auth:sanctum')->group(function () {
        // Ouvrier
        Route::post('/ouvrier/location', [OuvrierLocationController::class, 'store'])->name('ouvrier.location.store');
        Route::post('/ouvrier', [OuvrierController::class, 'store'])->name('ouvrier.store');
        Route::put('/ouvrier/{ouvrier}', [OuvrierController::class, 'update'])->name('ouvrier.update');
        Route::get('/ouvrier/rechercher', [OuvrierController::class, 'rechercher'])->name('ouvrier.rechercher');
        Route::get('/ouvrier/create', [OuvrierController::class, 'create'])->name('ouvrier.create');
        Route::get('/ouvrier/{ouvrier}', [OuvrierController::class, 'show'])->name('ouvrier.show');
        Route::get('/ouvrier/', [OuvrierController::class, 'index'])->name('ouvrier.index');
        });
        // Auth
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

});
