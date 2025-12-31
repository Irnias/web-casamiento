<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RsvpController;

Route::prefix('{event:slug}')->group(function () {

    Route::get('/', [RsvpController::class, 'showLogin'])->name('login');
    Route::post('/login', [RsvpController::class, 'processLogin'])->name('login.process');
    Route::get('/logout', [RsvpController::class, 'logout'])->name('logout');
    Route::get('/invitacion', [RsvpController::class, 'index'])->name('rsvp.index');
});
