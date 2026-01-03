<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\RsvpController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Logout Global para Admins
Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
Route::any('/admin/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('auth.logout');

Route::prefix('{event:slug}')->group(function () {

    // 1. PÚBLICO (Login Invitados)
    Route::get('/', function ($event) {
        return redirect()->route('rsvp.login', $event);
    });
    Route::get('/login', [RsvpController::class, 'login'])->name('rsvp.login');
    Route::post('/login', [RsvpController::class, 'authenticate'])->name('rsvp.authenticate');
    Route::post('/logout', [RsvpController::class, 'logout'])->name('rsvp.logout');

    // 2. PRIVADO INVITADOS (Con Código)
    Route::middleware('guest.auth')->group(function () {
        Route::get('/invitacion', [RsvpController::class, 'index'])->name('rsvp.index');
        Route::post('/invitacion', [RsvpController::class, 'submit'])->name('rsvp.submit');
        Route::get('/fotos', [PhotoController::class, 'index'])->name('photos.index');
        Route::post('/fotos', [PhotoController::class, 'store'])->name('photos.store');
    });

    // 3. ADMIN (Dashboard - Requiere User Auth)
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/fotos', [PhotoController::class, 'pending'])->name('photos.pending');
        Route::get('/dashboard/invitados', [GuestController::class, 'index'])->name('guests.index');

        // Gestión de fotos
        Route::post('/fotos/{photo}/approve', [PhotoController::class, 'approve'])->name('photos.approve');
        Route::delete('/fotos/{photo}', [PhotoController::class, 'destroy'])->name('photos.destroy');

        // Gestión de Invitados
        Route::post('/invitados', [GuestController::class, 'store'])->name('guests.store');
        Route::delete('/invitados/{guest}', [GuestController::class, 'destroy'])->name('guests.destroy');
    });

    // 4. Login Magico por url (Para invitados es mas comodo)
    Route::get('/{code}', [RsvpController::class, 'magicLogin'])->name('rsvp.magic');
});
