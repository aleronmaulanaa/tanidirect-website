<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PriceTrackerController;

Route::view('/', 'welcome');

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
require __DIR__.'/auth.php';