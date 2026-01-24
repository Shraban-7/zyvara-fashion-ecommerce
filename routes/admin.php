<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;


Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/orders', function () {
        return redirect()->route('admin.dashboard');
    })->name('orders.index');
    Route::get('/orders/{id}', function () {
        return redirect()->route('admin.dashboard');
    })->name('orders.show');

    // Products Routes (placeholder - to be created later)
    Route::get('/products', function () {
        return redirect()->route('admin.dashboard');
    })->name('products.index');
    Route::get('/products/create', function () {
        return redirect()->route('admin.dashboard');
    })->name('products.create');

    // Categories Routes (placeholder)
    Route::get('/categories', function () {
        return redirect()->route('admin.dashboard');
    })->name('categories.index');

    // Reviews Routes (placeholder)
    Route::get('/reviews', function () {
        return redirect()->route('admin.dashboard');
    })->name('reviews.index');

    // Users Routes (placeholder)
    Route::get('/users', function () {
        return redirect()->route('admin.dashboard');
    })->name('users.index');

    // Coupons Routes (placeholder)
    Route::get('/coupons', function () {
        return redirect()->route('admin.dashboard');
    })->name('coupons.index');
    Route::get('/coupons/create', function () {
        return redirect()->route('admin.dashboard');
    })->name('coupons.create');

    // Banners Routes (placeholder)
    Route::get('/banners', function () {
        return redirect()->route('admin.dashboard');
    })->name('banners.index');

    // Settings Routes (placeholder)
    Route::get('/settings', function () {
        return redirect()->route('admin.dashboard');
    })->name('settings.index');
});
