<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MejaController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderanController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DapurOrderController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\CustomerOrderController;



// ⛔ GUEST
Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/', [LoginController::class, 'login']);
});

// ✅ LOGOUT universal untuk semua role
Route::middleware('auth')->post('/logout', [LoginController::class, 'logout'])->name('logout');

// ✅ ADMIN
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');

    Route::resource('kategori', KategoriController::class);
    Route::resource('menu', MenuController::class);
    Route::resource('meja', MejaController::class);
    Route::resource('orderan', OrderanController::class);
    Route::get('/orderan/{id}/print', [OrderanController::class, 'print'])->name('orderan.print');

    Route::get('/riwayat-transaksi', [RiwayatController::class, 'index'])->name('riwayat.index');
    Route::put('/riwayat-transaksi/{id}', [RiwayatController::class, 'update'])->name('riwayat.update');
    Route::delete('/riwayat-transaksi/{id}', [RiwayatController::class, 'destroy'])->name('riwayat.destroy');
    Route::get('/riwayat-transaksi/export', [RiwayatController::class, 'exportExcel'])->name('riwayat.export');

    Route::resource('user', UserController::class);
    Route::resource('reservasi', ReservationController::class);
});

// ✅ KASIR
Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/dashboard', fn() => view('kasir.dashboard'))->name('dashboard');

    Route::resource('orderan', OrderanController::class)->only(['index', 'show', 'print', 'store', 'update']);
    Route::get('/orderan/{id}/print', [OrderanController::class, 'print'])->name('orderan.print');

    Route::resource('reservasi', ReservationController::class);
});

// ✅ DAPUR
Route::middleware(['auth', 'role:dapur'])->prefix('dapur')->name('dapur.')->group(function () {
    Route::get('/dashboard', fn() => view('dapur.dashboard'))->name('dashboard');
    Route::get('/dapur/orderan', [DapurOrderController::class, 'index'])->name('dapur.orderan.index');
    // Route::post('/dapur/orderan/{id}/selesai', [DapurOrderController::class, 'updateStatus'])->name('dapur.orderan.selesai');
    Route::get('/dapur/orderan/json', [DapurOrderController::class, 'json'])->name('dapur.orderan.json');
    // Route::post('/dapur/reservasi/{id}/selesai', [DapurOrderController::class, 'updateStatus'])->name('dapur.reservasi.selesai');
    Route::post('/dapur/orderan/{id}/selesai',    [DapurOrderController::class, 'updateStatus'])
      ->name('dapur.orderan.selesai')    ->defaults('tipe', 'order');
    Route::post('/dapur/reservasi/{id}/selesai', [DapurOrderController::class, 'updateStatus'])
        ->name('dapur.reservasi.selesai') ->defaults('tipe', 'reservasi');

});

// ✅ ORDER VIA QR / TANPA LOGIN
Route::get('/order/meja/{id}', [OrderController::class, 'index'])->name('order.index');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
//reservasi
Route::get('/reservasi', [ReservationController::class, 'create'])->name('reservasi.create');
Route::post('/reservasi', [ReservationController::class, 'store'])->name('reservasi.store');
Route::get('/reservasi/{id}/download', [ReservationController::class, 'download'])
     ->name('reservasi.download');



