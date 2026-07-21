<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PriceTrackerController;
use App\Http\Controllers\OrderPoolController;
use App\Http\Controllers\LandingController;

Route::get('/', [LandingController::class, 'index'])
    ->name('landing');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::view('/dashboard', 'dashboard')
        ->name('dashboard');

});

Route::middleware(['auth', 'verified', 'admin'])->group(function () {

    Route::view('/admin/dashboard', 'admin.dashboard')
        ->name('admin.dashboard');

});

Route::view('/profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');
    
Route::middleware(['auth'])->group(function () {

    Route::get('/price-tracker', [PriceTrackerController::class, 'index'])
        ->name('price-tracker');

});

Route::middleware('auth')->group(function () {
    Route::controller(OrderPoolController::class)
        ->prefix('order-pool')
        ->name('order-pool.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{orderPool}', 'show')->name('show');
            Route::post('/{orderPool}/join', 'join')->name('join');
        });
});

require __DIR__.'/auth.php';