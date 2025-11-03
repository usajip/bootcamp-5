<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{id}', [HomeController::class, 'detailProduct'])->name('product.detail');
Route::get('cart', [HomeController::class, 'cart'])->name('cart');
Route::get('checkout', [HomeController::class, 'checkout'])->name('checkout');

Route::middleware('auth')->group(function () {
    
    Route::prefix('dashboard')
        ->middleware('admin')
        ->group(function () {
        // Route::get('/index', function () {
        //     return view('dashboard');
        // })->middleware(['auth', 'verified'])->name('dashboard');
        Route::get('/index', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('products', ProductController::class);
        Route::resource('categories', ProductCategoryController::class);
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
