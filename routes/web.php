<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

// ===== Halaman publik =====
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/islamic-values', [PageController::class, 'islamic'])->name('islamic');
Route::get('/economic-discussion', [PageController::class, 'economic'])->name('economic');
Route::get('/our-team', [PageController::class, 'team'])->name('team');

// Halaman Order tetap publik supaya simulator Paylater bisa dicoba tanpa akun.
Route::get('/order', [OrderController::class, 'index'])->name('order');

Route::get('/lang/{locale}', [LocaleController::class, 'switch'])
    ->whereIn('locale', SetLocale::SUPPORTED)
    ->name('lang.switch');

// ===== Wajib login =====
Route::middleware('auth')->group(function () {
    Route::post('/cart', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update')->whereNumber('product');
    Route::delete('/cart/{product}', [CartController::class, 'remove'])->name('cart.remove')->whereNumber('product');

    Route::post('/order', [OrderController::class, 'store'])->name('order.store');
    Route::get('/order/{order}', [OrderController::class, 'show'])->name('order.show')->whereNumber('order');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';