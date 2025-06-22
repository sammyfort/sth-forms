<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->as('profile.')->group(function () {
    Route::get('profile', [ProfileController::class, 'show'])->name('show');
    Route::patch('profile/edit/personal', [ProfileController::class, 'editPersonalDetails'])->name('edit-personal');
    Route::patch('profile/edit/socials', [ProfileController::class, 'editSocials'])->name('edit-socials');
    Route::post('profile/edit/avatar', [ProfileController::class, 'uploadAvatar'])->name('edit-avatar');

    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');
});

require __DIR__.'/auth.php';
