<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// The whole site is private: send visitors straight to sign-in / dashboard.
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Members-only area: must be signed in AND approved by an admin.
Route::middleware(['auth', 'approved'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
