<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{id}', [HomeController::class, 'detailProduct'])->name('product.detail');
Route::get('cart', [HomeController::class, 'cart'])->name('cart');
Route::get('checkout', [HomeController::class, 'checkout'])->name('checkout');

Route::prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('products', ProductController::class);
    Route::resource('categories', ProductCategoryController::class);
});

// Route::get('/product', [ProductController::class, 'index']);
// Route::get('/create', [ProductController::class, 'create']);
// Route::post('/store', [ProductController::class, 'store']);


Route::get('user/{id}/{name}', function ($id, $name) {
    return "User ID: " . $id . ", Name: " . $name;
});

Route::post('/submit', function () {
    return "Form Submitted";
});

Route::put('/update', function () {
    return "Resource Updated";
});

Route::delete('/delete', function () {
    return "Resource Deleted";
});

