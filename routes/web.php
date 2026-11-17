<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocaleController;
use App\Http\Middleware\SetLocale;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/lang/{locale}', [LocaleController::class, 'switch'])
    ->whereIn('locale', SetLocale::SUPPORTED)
    ->name('lang.switch');

    Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/islamic-values', [PageController::class, 'islamic'])->name('islamic');
Route::get('/economic-discussion', [PageController::class, 'economic'])->name('economic');
Route::get('/our-team', [PageController::class, 'team'])->name('team');

Route::get('/order', [OrderController::class, 'index'])->name('order');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
Route::get('/order/{order}', [OrderController::class, 'show'])->name('order.show')->whereNumber('order');

Route::post('/cart', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update')->whereNumber('product');
Route::delete('/cart/{product}', [CartController::class, 'remove'])->name('cart.remove')->whereNumber('product');

require __DIR__.'/auth.php';
