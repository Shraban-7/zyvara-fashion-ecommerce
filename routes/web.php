<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('auth')->as('auth.')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::prefix('products')->as('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/{slug}', [ProductController::class, 'show'])->name('show');
    Route::get('/{product}/quickview', [ProductController::class, 'getQuickviewData'])->name('getQuickviewData');
});

Route::prefix('cart')->as('cart.')->group(function () {
    Route::get('/', [CartController::class, 'getCart'])->name('get');
    Route::post('/add', [CartController::class, 'addToCart'])->name('add');
    Route::put('/update/{itemId}', [CartController::class, 'updateQuantity'])->name('update');
    Route::delete('/remove/{itemId}', [CartController::class, 'removeItem'])->name('remove');
    Route::delete('/clear', [CartController::class, 'clearCart'])->name('clear');
});

Route::prefix('checkout')->as('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/', [CheckoutController::class, 'store'])->name('store');
    Route::post('/validate-coupon', [CheckoutController::class, 'validateCoupon'])->name('validate-coupon');
    Route::get('/success', [CheckoutController::class, 'success'])->name('success');
});

Route::middleware('auth')->group(function () {
    // Customer Dashboard
    Route::as('customer.')->group(function () {
        Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [CustomerController::class, 'profile'])->name('profile');
        Route::put('/profile', [CustomerController::class, 'updateProfile'])->name('profile.update');
        Route::put('/password', [CustomerController::class, 'updatePassword'])->name('password.update');
        Route::get('/addresses', [CustomerController::class, 'addresses'])->name('addresses');
        Route::post('/addresses', [CustomerController::class, 'storeAddress'])->name('addresses.store');
        Route::put('/addresses/{address}', [CustomerController::class, 'updateAddress'])->name('addresses.update');
        Route::delete('/addresses/{address}', [CustomerController::class, 'deleteAddress'])->name('addresses.delete');
        Route::get('/wishlist', [CustomerController::class, 'wishlist'])->name('wishlist');
        Route::get('/reviews', [CustomerController::class, 'reviews'])->name('reviews');
    });

    // Orders
    Route::prefix('orders')->as('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order:order_number}/show', [OrderController::class, 'show'])->name('show');
    });
});

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout.old');
