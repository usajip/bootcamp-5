<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{id}', [HomeController::class, 'detailProduct'])->name('product.detail');

Route::middleware('auth')->group(function () {
    Route::resource('cart', CartController::class)->only([
        'index', 'store', 'update', 'destroy'
    ]);

    Route::get('checkout', [CartController::class, 'checkoutPage'])->name('checkout');

    Route::post('checkout/process', [TransactionController::class, 'store'])->name('transaction.store');
    
    Route::get('transaction/{transaction}', [TransactionController::class, 'show'])->name('transaction.show');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::prefix('dashboard')
        ->middleware('admin')->group(function () {
        // Route::get('/index', function () {
        //     return view('dashboard');
        // })->middleware(['auth', 'verified'])->name('dashboard');
        Route::get('/index', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('products', ProductController::class);
        Route::resource('categories', ProductCategoryController::class);
    });
});

require __DIR__.'/auth.php';
