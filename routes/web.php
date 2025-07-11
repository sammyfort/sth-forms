<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SignboardController;
use App\Http\Controllers\SignboardSubscriptionPaymentController;
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

    Route::prefix('my-signboards')->as('my-signboards.')->group(function () {
        Route::get('/', [SignboardController::class, 'mySignboards'])->name('index');
        Route::get('/create', [SignboardController::class, 'create'])->name('create');
        Route::post('/store', [SignboardController::class, 'store'])->name('store');
        Route::get('/{signboard:slug}', [SignboardController::class, 'showMySignboard'])->name('show');
        Route::get('/edit/{signboard:slug}', [SignboardController::class, 'edit'])->name('edit');
        Route::post('/{signboard}', [SignboardController::class, 'update'])->name('update');
        Route::delete('/{signboard}', [SignboardController::class, 'delete'])->name('delete');
    });

    Route::prefix('signboards')->as('signboards.')->group(function () {
        Route::post('/{signboard}/ratings',  [SignboardController::class, 'rate'])->name('ratings');
    });
});

Route::get('api/{path}', ApiController::class)->name('api');



Route::prefix('businesses')->name('businesses.')->group(function () {
    Route::get('/', [BusinessController::class, 'index'])->name('index');
});

Route::prefix('signboards')->as('signboards.')->group(function () {
    Route::get('/', [SignboardController::class, 'index'])->name('index');
    Route::get('/{signboard:slug}/details', [SignboardController::class, 'show'])->name('show');
    Route::get('/promoted', [SignboardController::class, 'getPromotedSignboards'])->name('promoted');
});

Route::post('contact-us', [ContactUsController::class, 'store'])->name('contact-us');
Route::get('faq', [FaqController::class, 'index'])->name('faq.index');
Route::get('about-us', fn()=> Inertia::render('AboutUs'))->name('about-us');
Route::get('privacy-policy', fn()=> Inertia::render('PrivacyPolicy'))->name('privacy-policy');

Route::prefix('payments')->as('payments.')->group(function () {
    Route::post('signboard-subscription', [SignboardSubscriptionPaymentController::class, 'initializeHubtel'])
        ->middleware(['auth', 'verified'])->name('signboard-subscription');

    Route::get('signboard-subscription/verify', [SignboardSubscriptionPaymentController::class, 'verifyHubtel'])
        ->name('signboard-subscription.verify');
});

require __DIR__.'/auth.php';
