<?php


use App\Http\Controllers\DashboardController;
use App\Http\Controllers\{HomeController, ResearchController, VoucherController};
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::resource('/buy-voucher', VoucherController::class);
Route::get('/research-application', [ResearchController::class, 'index'])->name('research.apply');


Route::post('/research/validate/{step}', [ResearchController::class, 'validateStep'])->name('research.validateStep');
Route::post('/research/submit', [ResearchController::class, 'submit'])->name('research.submit');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

});




require __DIR__.'/auth.php';
