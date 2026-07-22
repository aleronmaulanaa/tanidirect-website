<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\LandingController;
use App\Http\Controllers\PriceTrackerController;
use App\Http\Controllers\OrderPoolController;

use App\Http\Controllers\ProducerAuthController;
use App\Http\Controllers\BuyerAuthController;
use App\Http\Controllers\BuyerDashboardController;


/*
|--------------------------------------------------------------------------
| Landing Page
|--------------------------------------------------------------------------
*/

Route::get('/', [LandingController::class, 'index'])
    ->name('landing');



/*
|--------------------------------------------------------------------------
| General Authenticated User
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    Route::view('/dashboard', 'dashboard')
        ->name('dashboard');


});



/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'admin'])->group(function () {

    Route::view('/admin/dashboard', 'admin.dashboard')
        ->name('admin.dashboard');

});



/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/

Route::view('/profile', 'profile')
    ->middleware('auth')
    ->name('profile');




/*
|--------------------------------------------------------------------------
| Price Tracker
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/price-tracker', [
        PriceTrackerController::class,
        'index'
    ])
    ->name('price-tracker');

});




/*
|--------------------------------------------------------------------------
| Order Pool
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::controller(OrderPoolController::class)
        ->prefix('order-pool')
        ->name('order-pool.')
        ->group(function () {

            Route::get('/', 'index')
                ->name('index');


            Route::get('/{orderPool}', 'show')
                ->name('show');


            Route::post('/{orderPool}/join', 'join')
                ->name('join');

        });

});




/*
|--------------------------------------------------------------------------
| Producer Authentication & Dashboard
|--------------------------------------------------------------------------
*/

Route::prefix('producer')->group(function () {


    Route::get('/login', function () {

        return view('producer.auth.login');

    })
    ->name('producer.login');



    Route::post('/login', [
        ProducerAuthController::class,
        'login'
    ])
    ->name('producer.login.process');



    Route::get('/register', function () {

        return view('producer.auth.register');

    })
    ->name('producer.register');



    Route::post('/register', [
        ProducerAuthController::class,
        'register'
    ])
    ->name('producer.register.process');



    Route::post('/logout', [
        ProducerAuthController::class,
        'logout'
    ])
    ->name('producer.logout');



    Route::get('/dashboard', function () {

        return view('producer.dashboard');

    })
    ->middleware('auth')
    ->name('producer.dashboard');


});





/*
|--------------------------------------------------------------------------
| Buyer Authentication
|--------------------------------------------------------------------------
*/

Route::prefix('buyer')->group(function () {


    Route::get('/login', function () {

        return view('buyer.auth.login');

    })
    ->name('buyer.login');



    Route::post('/login', [
        BuyerAuthController::class,
        'login'
    ])
    ->name('buyer.login.process');



    Route::get('/register', function () {

        return view('buyer.auth.register');

    })
    ->name('buyer.register');



    Route::post('/register', [
        BuyerAuthController::class,
        'register'
    ])
    ->name('buyer.register.process');


});





/*
|--------------------------------------------------------------------------
| Buyer Dashboard
|--------------------------------------------------------------------------
*/

Route::prefix('buyer')
    ->middleware('auth')
    ->group(function () {


        Route::get('/dashboard', [
            BuyerDashboardController::class,
            'index'
        ])
        ->name('buyer.dashboard');


    });





/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

Route::post('/logout', function () {

    Auth::logout();

    request()
        ->session()
        ->invalidate();


    request()
        ->session()
        ->regenerateToken();


    return redirect('/');


})
->name('logout');



require __DIR__.'/auth.php';