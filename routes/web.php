<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PhotoController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RsvpController;
use Illuminate\Support\Facades\Route;

Route::any('/admin/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('auth.logout');

Route::prefix('{event:slug}')->group(function () {

    Route::get('/', [RsvpController::class, 'showLogin'])->name('event.login');
    Route::post('/login', [RsvpController::class, 'processLogin'])->name('login.process');
    Route::get('/logout', [RsvpController::class, 'logout'])->name('guest.logout');

    Route::middleware('guest.auth')->group(function () {
        Route::get('/invitacion', [RsvpController::class, 'index'])->name('rsvp.index');
        Route::get('/fotos', [PhotoController::class, 'index'])->name('photos.index');
        Route::post('/fotos', [PhotoController::class, 'store'])->name('photos.store');
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });

});
