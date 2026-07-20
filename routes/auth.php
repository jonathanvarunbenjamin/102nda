<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\GoogleController;
use Illuminate\Support\Facades\Route;

/*
 | Authentication is Google-only (SSO). We keep Breeze's session handling
 | for logout, but sign-in happens entirely through Google.
 */

Route::middleware('guest')->group(function () {
    // The login screen: just a "Sign in with Google" button.
    Route::view('login', 'auth.login')->name('login');

    // Google SSO handshake.
    Route::get('auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
    Route::get('auth/google/callback', [GoogleController::class, 'callback']);
});

Route::middleware('auth')->group(function () {
    // Waiting room for members who haven't been approved yet.
    Route::view('pending', 'auth.pending')->name('pending');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
