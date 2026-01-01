<?php

use App\Http\Controllers\PhotoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RsvpController;

Route::prefix('{event:slug}')->group(function () {

    Route::get('/', [RsvpController::class, 'showLogin'])->name('login');
    Route::post('/login', [RsvpController::class, 'processLogin'])->name('login.process');
    Route::get('/logout', [RsvpController::class, 'logout'])->name('logout');
    Route::get('/invitacion', [RsvpController::class, 'index'])->name('rsvp.index');
});

Route::prefix('{event:slug}')->group(function () {

    Route::get('/', [RsvpController::class, 'showLogin'])->name('login');
    Route::post('/login', [RsvpController::class, 'processLogin'])->name('login.process');
    Route::get('/logout', [RsvpController::class, 'logout'])->name('logout');Route::middleware('guest.auth')->group(function () {

        Route::get('/invitacion', [RsvpController::class, 'index'])->name('rsvp.index');

        Route::get('/fotos', [PhotoController::class, 'index'])->name('photos.index');
        Route::post('/fotos', [PhotoController::class, 'store'])->name('photos.store');
    });
});
