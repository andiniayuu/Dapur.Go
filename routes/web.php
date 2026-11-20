<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CatalogController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuController;

use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\OrderController;

// Homepage
Route::get('/', [HomeController::class, 'index'])
    ->name('home');

// Katalog menu publik
Route::get('/menu', [CatalogController::class, 'index'])
    ->name('catalog.index');

Route::get('/menu/{menu:slug}', [CatalogController::class, 'show'])
    ->name('catalog.show');

// Login dan registrasi
Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1');

    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:5,1');
});

// Halaman yang memerlukan login
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {

        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('user.dashboard');
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});

// Halaman khusus Admin
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('menus', MenuController::class)
            ->except(['show']);
    });

// Halaman khusus Pengguna
Route::prefix('user')
    ->name('user.')
    ->middleware(['auth', 'role:user'])
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('user.dashboard');
        })->name('dashboard');

        // Keranjang
        Route::get('/cart', [CartController::class, 'index'])
            ->name('cart.index');

        Route::post('/cart/items/{menu}', [CartController::class, 'add'])
            ->name('cart.add');

        Route::patch('/cart/items/{menu}', [CartController::class, 'update'])
            ->name('cart.update');

        Route::delete('/cart/items/{menu}', [CartController::class, 'remove'])
            ->name('cart.remove');

        // Checkout
        Route::get('/checkout', [CheckoutController::class, 'create'])
            ->name('checkout.create');

        Route::post('/checkout', [CheckoutController::class, 'store'])
            ->name('checkout.store');

        // Riwayat pesanan
        Route::get('/orders', [OrderController::class, 'index'])
            ->name('orders.index');

        Route::get('/orders/{order}', [OrderController::class, 'show'])
            ->name('orders.show');
    });
