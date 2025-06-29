<?php

use App\Http\Controllers\BusinessController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SignboardController;
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
        Route::put('password', [ProfileController::class, 'updatePassword'])->name('password');
    });

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('my-businesses')->name('my-businesses.')->group(function () {
        Route::get('/', [BusinessController::class, 'myBusinesses'])->name('index');
        Route::post('/create', [BusinessController::class, 'create'])->name('create');
        Route::get('/{business:slug}', [BusinessController::class, 'show'])->name('show');
        Route::put('/{business}', [BusinessController::class, 'update'])->name('update');
        Route::delete('/{business}', [BusinessController::class, 'delete'])->name('delete');
    });

    Route::prefix('signboard')->as('signboard.')->group(function () {
        Route::get('/', [SignboardController::class, 'signboards'])->name('index');
        Route::post('/create', [SignboardController::class, 'create'])->name('create');
        Route::get('/{signboard:slug}', [SignboardController::class, 'show'])->name('show');
        Route::put('/{signboard}', [SignboardController::class, 'update'])->name('update');
        Route::delete('/{signboard}', [SignboardController::class, 'delete'])->name('delete');
    });


});



Route::prefix('businesses')->name('businesses.')->group(function () {
    Route::get('/', [BusinessController::class, 'index'])->name('index');
});



Route::prefix('signboards')->name('signboards.')->group(function () {
    Route::get('/', [SignboardController::class, 'index'])->name('index');
    Route::get('/promoted', [SignboardController::class, 'promoted'])->name('promoted');
});

require __DIR__.'/auth.php';
