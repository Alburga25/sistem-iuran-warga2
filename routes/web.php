<?php

use App\Http\Controllers\ProfileController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IuranController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\UserController;


use App\Http\Controllers\DashboardController;

Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('iurans', IuranController::class);
});

Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::resource('users', UserController::class);
});

Route::middleware('auth', AdminMiddleware::class)->group(function () {
    Route::get('/tagihan', [TagihanController::class, 'index'])->name('tagihan.index');
    Route::post('/tagihan', [TagihanController::class, 'store'])->name('tagihan.store');
    Route::get('/tagihan/laporan', [TagihanController::class, 'laporan'])->name('tagihan.laporan');
    Route::post('/tagihan/{tagihan}/bayar', [TagihanController::class, 'bayar'])->name('tagihan.bayar');
    Route::get('/bukti-pembayaran', [TagihanController::class, 'buktiPembayaran'])->name('tagihan.bukti-pembayaran');
    Route::get('/tagihan/semua-warga', [TagihanController::class, 'semuaWargaTagihan'])->name('tagihan.semuaWarga');
    // Menampilkan halaman notifikasi WA
    Route::get('/tagihan/notifikasi', function () {
        return view('tagihan.notifikasi');
    })->name('tagihan.notifikasi');

    Route::post('/trigger-notifikasi-wa', [TagihanController::class, 'triggerNotifikasiWABelumBayar'])
        ->name('trigger.notifikasi.wa');

    Route::post('/update-status', [TagihanController::class, 'updateStatus'])->name('tagihan.updateStatus');
});

Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
