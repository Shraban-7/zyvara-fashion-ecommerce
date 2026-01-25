<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CartController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('products')->as('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/{slug}', [ProductController::class, 'show'])->name('show');
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

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');
