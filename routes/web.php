<?php

use App\Http\Controllers\BusinessController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::patch('edit/personal', [ProfileController::class, 'editPersonalDetails'])->name('edit-personal');
        Route::patch('edit/socials', [ProfileController::class, 'editSocials'])->name('edit-socials');
        Route::post('edit/avatar', [ProfileController::class, 'uploadAvatar'])->name('edit-avatar');
        Route::put('password', [ProfileController::class, 'updatePassword'])->name('password.update');
    });

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('businesses')->name('businesses.')->group(function () {
        Route::get('mine', [BusinessController::class, 'myBusinesses'])->name('mine');
    });
});

Route::prefix('businesses')->name('businesses.')->group(function () {
    Route::get('/', [BusinessController::class, 'index'])->name('index');
});

require __DIR__.'/auth.php';
