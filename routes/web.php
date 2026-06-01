<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController; 
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomResetController; // <-- Ini tambahan baru buat ngatur reset password

Route::get('/', function () {
    return view('welcome');
});

// Route bawaan dari fitur Login/Register Bootstrap tadi
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// =========================================================================
// RUTE BARU: ALUR LUPA PASSWORD MANUAL (KHUSUS UMKM YANG BELUM LOGIN)
// =========================================================================
Route::get('/lupa-password-umkm', [CustomResetController::class, 'index'])->name('custom.forgot');
Route::post('/lupa-password-umkm/cek', [CustomResetController::class, 'cekStatus'])->name('custom.cek');
Route::post('/lupa-password-umkm/ubah', [CustomResetController::class, 'ubahPassword'])->name('custom.ubah');
// =========================================================================


// Peta jalan khusus transaksi (dikasih middleware auth biar yang belum login nggak bisa buka)
Route::middleware(['auth'])->group(function () {
    Route::get('/transactions/export', [TransactionController::class, 'exportPdf'])->name('transactions.export');
    Route::resource('transactions', TransactionController::class);
});


// Contoh kalau diubah jadi super-admin
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('super-admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/user/{id}/toggle', [AdminController::class, 'toggleStatus'])->name('admin.user.toggle');

    // Route reset password paksa yang lama (tetep disimpen aja buat jaga-jaga)
    Route::post('/user/{id}/reset-password', [AdminController::class, 'resetPassword'])->name('admin.user.reset');

    // RUTE BARU: ADMIN NGE-ACC PERMINTAAN RESET PASSWORD UMKM
    Route::post('/user/{id}/acc-reset', [AdminController::class, 'accReset'])->name('admin.user.acc_reset');
});