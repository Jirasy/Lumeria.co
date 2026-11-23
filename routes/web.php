<?php

<<<<<<< HEAD
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

=======

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
>>>>>>> origin/main
use App\Http\Controllers\CartController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;
<<<<<<< HEAD


/*
|--------------------------------------------------------------------------
| Tiga perspektif (arahan Pak CEO)
|--------------------------------------------------------------------------
| 1. TAMU      : etalase produk, detail produk, info pemesanan & simulasi Paylater.
| 2. PELANGGAN : (group 'auth') keranjang, checkout, Pesanan Saya, profil, Our Team.
| 3. ADMIN     : (group 'auth' + 'admin') panel toko dan materi Nilai Islam / Diskusi Ekonomi.
*/


// ---------- 1. Tamu (publik) ----------
=======
>>>>>>> origin/main


/*
|--------------------------------------------------------------------------
| Tiga perspektif (arahan Pak CEO)
|--------------------------------------------------------------------------
| 1. TAMU      : etalase produk, detail produk, info pemesanan & simulasi Paylater.
| 2. PELANGGAN : (group 'auth') keranjang, checkout, Pesanan Saya, profil, Our Team.
| 3. ADMIN     : (group 'auth' + 'admin') panel toko dan materi Nilai Islam / Diskusi Ekonomi.
*/


// ---------- 1. Tamu (publik) ----------
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/islamic-values', [PageController::class, 'islamic'])->name('islamic');
Route::get('/economic-discussion', [PageController::class, 'economic'])->name('economic');
Route::get('/our-team', [PageController::class, 'team'])->name('team');

<<<<<<< HEAD

// Halaman Order tetap publik supaya simulator Paylater bisa dicoba tanpa akun.
Route::get('/order', [OrderController::class, 'index'])->name('order');


=======
>>>>>>> origin/main
Route::get('/lang/{locale}', [LocaleController::class, 'switch'])
    ->whereIn('locale', SetLocale::SUPPORTED)
    ->name('lang.switch');

<<<<<<< HEAD

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

Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

=======
Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

>>>>>>> origin/main
// Info pemesanan + simulator Paylater tetap publik. Keranjang dan checkout ada di group 'auth' di bawah.
Route::get('/order', [OrderController::class, 'index'])->name('order');

// ---------- 2. Pelanggan (sudah login) ----------
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('verified')->name('dashboard');
<<<<<<< HEAD
=======

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/cart', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update')->whereNumber('product');
    Route::delete('/cart/{product}', [CartController::class, 'remove'])->name('cart.remove')->whereNumber('product');

    Route::post('/order', [OrderController::class, 'store'])->name('order.store');
    // Detail pesanan: tamu diarahkan ke login; pemilik dan admin boleh, pengguna lain 403 (lihat OrderController::show).
    Route::get('/order/{order}', [OrderController::class, 'show'])->name('order.show')->whereNumber('order');
    Route::get('/orders', [OrderController::class, 'history'])->name('orders.index');

    // Our Team: disembunyikan dari tamu. Untuk membatasi ke admin saja, pindahkan baris ini ke group admin di bawah.
    Route::get('/our-team', [PageController::class, 'team'])->name('team');
});


// ---------- 3. Admin ----------
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Bagian khusus / materi keagamaan dan diskusi. URL tetap sama seperti sebelumnya.
    Route::get('/islamic-values', [PageController::class, 'islamic'])->name('islamic');
    Route::get('/economic-discussion', [PageController::class, 'economic'])->name('economic');
});

>>>>>>> origin/main

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/cart', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update')->whereNumber('product');
    Route::delete('/cart/{product}', [CartController::class, 'remove'])->name('cart.remove')->whereNumber('product');

    Route::post('/order', [OrderController::class, 'store'])->name('order.store');
    // Detail pesanan: tamu diarahkan ke login; pemilik dan admin boleh, pengguna lain 403 (lihat OrderController::show).
    Route::get('/order/{order}', [OrderController::class, 'show'])->name('order.show')->whereNumber('order');
    Route::get('/orders', [OrderController::class, 'history'])->name('orders.index');

    // Our Team: disembunyikan dari tamu. Untuk membatasi ke admin saja, pindahkan baris ini ke group admin di bawah.
    Route::get('/our-team', [PageController::class, 'team'])->name('team');
});


// ---------- 3. Admin ----------
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Bagian khusus / materi keagamaan dan diskusi. URL tetap sama seperti sebelumnya.
    Route::get('/islamic-values', [PageController::class, 'islamic'])->name('islamic');
    Route::get('/economic-discussion', [PageController::class, 'economic'])->name('economic');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';