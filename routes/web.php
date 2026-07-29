<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\LandingController;
use App\Http\Controllers\PriceTrackerController;
use App\Http\Controllers\OrderPoolController;

use App\Http\Controllers\ProducerAuthController;
use App\Http\Controllers\BuyerAuthController;
use App\Http\Controllers\BuyerDashboardController;
use App\Http\Controllers\DashboardRedirectController;
use App\Http\Controllers\ProducerProductController;
use App\Http\Controllers\BuyerProductController;
use App\Http\Controllers\BuyerOrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ProducerOrderController;


/*
|--------------------------------------------------------------------------
| Landing Page
|--------------------------------------------------------------------------
*/

Route::get('/', [LandingController::class, 'index'])
    ->name('landing');

Route::post('/chatbot/message', [ChatbotController::class, 'message'])
    ->name('chatbot.message');


/*
|--------------------------------------------------------------------------
| General Authenticated User
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', DashboardRedirectController::class)
    ->middleware('auth')
    ->name('dashboard');



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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});




/*
|--------------------------------------------------------------------------
| Price Tracker
|--------------------------------------------------------------------------
*/

Route::middleware('role:pembeli,produsen')->group(function () {

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

Route::middleware('role:pembeli')->group(function () {

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
    ->middleware('guest')
    ->name('producer.login');



    Route::post('/login', [
        ProducerAuthController::class,
        'login'
    ])
    ->middleware('guest')
    ->name('producer.login.process');



    Route::get('/register', function () {

        return view('producer.auth.register');

    })
    ->middleware('guest')
    ->name('producer.register');



    Route::post('/register', [
        ProducerAuthController::class,
        'register'
    ])
    ->middleware('guest')
    ->name('producer.register.process');



    Route::get('/dashboard', function () {

        return view('producer.dashboard');

    })
    ->middleware('role:produsen')
    ->name('producer.dashboard');

    Route::middleware('role:produsen')
        ->controller(ProducerProductController::class)
        ->prefix('products')
        ->name('producer.products.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{product}/edit', 'edit')->name('edit');
            Route::put('/{product}', 'update')->name('update');
            Route::patch('/{product}/status', 'toggleStatus')->name('status');
            Route::delete('/{product}', 'destroy')->name('destroy');
        });

    Route::middleware('role:produsen')
        ->controller(ProducerOrderController::class)
        ->prefix('orders')
        ->name('producer.orders.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{order}', 'show')->name('show');
            Route::patch('/{order}/status', 'updateStatus')->name('updateStatus');
        });


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
    ->middleware('guest')
    ->name('buyer.login');



    Route::post('/login', [
        BuyerAuthController::class,
        'login'
    ])
    ->middleware('guest')
    ->name('buyer.login.process');



    Route::get('/register', function () {

        return view('buyer.auth.register');

    })
    ->middleware('guest')
    ->name('buyer.register');



    Route::post('/register', [
        BuyerAuthController::class,
        'register'
    ])
    ->middleware('guest')
    ->name('buyer.register.process');


});





/*
|--------------------------------------------------------------------------
| Buyer Dashboard
|--------------------------------------------------------------------------
*/

Route::prefix('buyer')
    ->middleware('role:pembeli')
    ->group(function () {


        Route::get('/dashboard', [
            BuyerDashboardController::class,
            'index'
        ])
        ->name('buyer.dashboard');

        Route::get('/products/{product}', [BuyerProductController::class, 'show'])
            ->name('buyer.products.show');

        Route::post('/products/{product}/orders/checkout', [BuyerOrderController::class, 'checkout'])
            ->name('buyer.orders.checkout');

        Route::post('/products/{product}/orders', [BuyerOrderController::class, 'store'])
            ->name('buyer.orders.store');

        Route::get('/orders/{order}/tracking', [BuyerOrderController::class, 'tracking'])
            ->name('buyer.orders.tracking');

        Route::post('/orders/{order}/review', [BuyerOrderController::class, 'storeReview'])
            ->name('buyer.orders.review');


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
