<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PriceTrackerController;
use App\Http\Controllers\ProducerAuthController;
use App\Http\Controllers\BuyerAuthController;
use App\Http\Controllers\OrderPoolController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Auth;

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

Route::prefix('producer')->group(function () {

    Route::get('/login', function () {

        return view('producer.auth.login');

    })->name('producer.login');

    Route::post('/login', [
        ProducerAuthController::class,
        'login'
    ])->name('producer.login.process');

    Route::get('/register', function () {

        return view('producer.auth.register');

    })->name('producer.register');

    Route::post('/register', [
        ProducerAuthController::class,
        'register'
    ])->name('producer.register.process');

    Route::post('/logout', [
        ProducerAuthController::class,
        'logout'
    ])->name('producer.logout');

    Route::get('/dashboard', function () {

        return view('producer.dashboard');

    })
    ->middleware('auth')
    ->name('producer.dashboard');

});

Route::prefix('buyer')->group(function () {

    Route::get('/login', function () {
        return view('buyer.auth.login');
    })->name('buyer.login');


    Route::post('/login', [
        BuyerAuthController::class,
        'login'
    ])->name('buyer.login.process');


    Route::get('/register', function () {
        return view('buyer.auth.register');
    })->name('buyer.register');


    Route::post('/register', [
        BuyerAuthController::class,
        'register'
    ])->name('buyer.register.process');


    Route::get('/dashboard', function () {
        return view('buyer.dashboard');
    })
    ->middleware('auth')
    ->name('buyer.dashboard');

});

Route::post('/logout', function () {

    Auth::logout();

    request()->session()->invalidate();

    request()->session()->regenerateToken();

    return redirect('/');

})->name('logout');
require __DIR__.'/auth.php';