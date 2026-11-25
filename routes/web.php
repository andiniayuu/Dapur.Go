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

use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\NotificationController;

use App\Http\Controllers\User\ProfileController;

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

    // Notifikasi
    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');

    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])
        ->name('notifications.read-all');

    Route::post('/notifications/{id}/read', [NotificationController::class, 'read'])
        ->name('notifications.read');
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

        // Manajemen pesanan
        Route::get('/orders', [AdminOrderController::class, 'index'])
            ->name('orders.index');

        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])
            ->name('orders.show');

        // Melihat bukti pembayaran
        Route::get(
            '/orders/{order}/payment-proof',
            [AdminOrderController::class, 'paymentProof']
        )->name('orders.payment-proof');

        // Konfirmasi atau penolakan pembayaran
        Route::patch(
            '/orders/{order}/approve-payment',
            [AdminOrderController::class, 'approvePayment']
        )->name('orders.approve-payment');

        Route::patch(
            '/orders/{order}/reject-payment',
            [AdminOrderController::class, 'rejectPayment']
        )->name('orders.reject-payment');

        // Memperbarui status pesanan
        Route::patch(
            '/orders/{order}/status',
            [AdminOrderController::class, 'updateStatus']
        )->name('orders.update-status');

        // Laporan
        Route::get('/reports', [ReportController::class, 'index'])
            ->name('reports.index');
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

        // Upload bukti pembayaran
        Route::post(
            '/orders/{order}/payment',
            [PaymentController::class, 'upload']
        )->name('payments.upload');

        // Profil pengguna
        Route::get('/profile', [ProfileController::class, 'edit'])
            ->name('profile.edit');

        Route::put('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');
    });
