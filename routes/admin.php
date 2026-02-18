<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ReviewController;

Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('products')->as('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/store', [ProductController::class, 'store'])->name('store');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('/{product}/update', [ProductController::class, 'update'])->name('update');
        Route::delete('/{product}/delete', [ProductController::class, 'destroy'])->name('delete');
    });

    Route::prefix('orders')->as('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{id}', [OrderController::class, 'show'])->name('show');
        Route::post('/{id}/update-status', [OrderController::class, 'updateStatus'])->name('update-status');
        Route::post('/{id}/update-tracking', [OrderController::class, 'updateTracking'])->name('update-tracking');
        Route::post('/{id}/update-notes', [OrderController::class, 'updateNotes'])->name('update-notes');
        Route::delete('/{id}', [OrderController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('categories')->as('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::post('/store', [CategoryController::class, 'store'])->name('store');
        Route::put('/{category}/update', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'delete'])->name('delete');
    });

    Route::prefix('reviews')->as('reviews.')->group(function () {
        Route::get('/', [ReviewController::class, 'index'])->name('index');
    });
    Route::prefix('customers')->as('customers.')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
    });


    // Categories Routes (placeholder)
    // Route::get('/categories', function () {
    //     return redirect()->route('admin.dashboard');
    // })->name('categories.index');

    // Reviews Routes (placeholder)
    // Route::get('/reviews', function () {
    //     return redirect()->route('admin.dashboard');
    // })->name('reviews.index');

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
