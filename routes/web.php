<?php

use App\Http\Controllers\Admin\MemberController as AdminMemberController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MemorialController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// The whole site is private: send visitors straight to sign-in / dashboard.
Route::get('/', fn () => redirect()->route('dashboard'));

// Members-only area: must be signed in AND approved by an admin.
Route::middleware(['auth', 'approved'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    // Own profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Member directory
    Route::get('/members', [MemberController::class, 'index'])->name('members.index');
    Route::get('/members/{member}', [MemberController::class, 'show'])->name('members.show');

    // Photo gallery
    Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
    Route::get('/gallery/create', [GalleryController::class, 'create'])->name('gallery.create');
    Route::post('/gallery', [GalleryController::class, 'store'])->name('gallery.store');
    Route::get('/gallery/{album}', [GalleryController::class, 'show'])->name('gallery.show');
    Route::post('/gallery/{album}/photos', [GalleryController::class, 'addPhotos'])->name('gallery.photos');
    Route::delete('/gallery/{album}', [GalleryController::class, 'destroyAlbum'])->name('gallery.album.destroy');
    Route::delete('/gallery/photo/{photo}', [GalleryController::class, 'destroyPhoto'])->name('gallery.photo.destroy');

    // Fallen Brothers memorial
    Route::get('/memorial', [MemorialController::class, 'index'])->name('memorial.index');
    Route::get('/memorial/create', [MemorialController::class, 'create'])->name('memorial.create');
    Route::post('/memorial', [MemorialController::class, 'store'])->name('memorial.store');
    Route::get('/memorial/{fallen}', [MemorialController::class, 'show'])->name('memorial.show');
    Route::delete('/memorial/{fallen}', [MemorialController::class, 'destroy'])->name('memorial.destroy');
    Route::post('/memorial/{fallen}/tribute', [MemorialController::class, 'storeTribute'])->name('memorial.tribute');
    Route::post('/memorial/{fallen}/photo', [MemorialController::class, 'storePhoto'])->name('memorial.photo');

    // Visit planning
    Route::get('/visit', [EventController::class, 'index'])->name('visit.index');
    Route::get('/visit/create', [EventController::class, 'create'])->name('visit.create');
    Route::post('/visit', [EventController::class, 'store'])->name('visit.store');
    Route::get('/visit/{event}', [EventController::class, 'show'])->name('visit.show');
    Route::delete('/visit/{event}', [EventController::class, 'destroy'])->name('visit.destroy');
    Route::post('/visit/{event}/rsvp', [EventController::class, 'rsvp'])->name('visit.rsvp');

    // Forums
    Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
    Route::get('/forum/create', [ForumController::class, 'create'])->name('forum.create');
    Route::post('/forum', [ForumController::class, 'store'])->name('forum.store');
    Route::get('/forum/{thread}', [ForumController::class, 'show'])->name('forum.show');
    Route::post('/forum/{thread}/reply', [ForumController::class, 'reply'])->name('forum.reply');
    Route::patch('/forum/{thread}/toggle', [ForumController::class, 'toggle'])->name('forum.toggle');
    Route::delete('/forum/{thread}', [ForumController::class, 'destroy'])->name('forum.destroy');

    // Admin area (only the 15 course admins)
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/members', [AdminMemberController::class, 'index'])->name('members.index');
        Route::patch('/members/{user}/status', [AdminMemberController::class, 'updateStatus'])->name('members.status');
        Route::patch('/members/{user}/role', [AdminMemberController::class, 'updateRole'])->name('members.role');
    });
});

require __DIR__.'/auth.php';
