
<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

// ---------- 1. Tamu (publik) ----------
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

// Informasi pemesanan dan simulator Paylater tetap publik.
Route::get('/order', [OrderController::class, 'index'])->name('order');

Route::get('/lang/{locale}', [LocaleController::class, 'switch'])
    ->whereIn('locale', SetLocale::SUPPORTED)
    ->name('lang.switch');

// ---------- 2. Pelanggan (sudah login) ----------
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('verified')->name('dashboard');

    // Profil dan avatar.
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/avatar/{user}', [ProfileController::class, 'avatar'])->name('avatar.show');

    // Keranjang.
    Route::post('/cart', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update')->whereNumber('product');
    Route::delete('/cart/{product}', [CartController::class, 'remove'])->name('cart.remove')->whereNumber('product');

    // Pesanan.
    Route::post('/order', [OrderController::class, 'store'])->name('order.store');
    Route::get('/order/{order}', [OrderController::class, 'show'])->name('order.show')->whereNumber('order');
    Route::get('/orders', [OrderController::class, 'history'])->name('orders.index');
});

// ---------- 3. Admin ----------
Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('users', AdminUserController::class);
    });

// Halaman khusus admin.
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/our-team', [PageController::class, 'team'])->name('team');
    Route::get('/islamic-values', [PageController::class, 'islamic'])->name('islamic');
    Route::get('/economic-discussion', [PageController::class, 'economic'])->name('economic');
    Route::get('/sejarah-indonesia/william-tanuwijaya', [PageController::class, 'williamTanuwijaya'])
    ->name('sejarah.william');

    // Kuis PAIBP.
    Route::get('/paibp-quiz', [QuizController::class, 'index'])->name('quiz.index');
    Route::post('/paibp-quiz/answer', [QuizController::class, 'answer'])->name('quiz.answer');
    Route::post('/paibp-quiz/finish', [QuizController::class, 'finish'])->name('quiz.finish');
    Route::get('/paibp-quiz/history', [QuizController::class, 'history'])->name('quiz.history');
    Route::get('/paibp-quiz/history/{attempt}', [QuizController::class, 'show'])->name('quiz.show');
});
require __DIR__.'/auth.php';
