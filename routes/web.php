<?php

use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsPelanggan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\admin\BulanController;
use App\Http\Controllers\admin\TarifController;
use App\Http\Controllers\admin\TagihanController;
use App\Http\Controllers\admin\PelangganController;
use App\Http\Controllers\admin\DashboardController as AdminDashboard;
use App\Http\Controllers\pelanggan\DashboardController as PelangganDashboard;
use App\Http\Controllers\pelanggan\PelangganDashboardController;

/*
|--------------------------------------------------------------------------
| Halaman Login Utama
|--------------------------------------------------------------------------
*/

// Saat user mengakses '/', langsung diarahkan ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// Form login & proses login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Rute yang Memerlukan Autentikasi
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD OTOMATIS (Redirect berdasarkan role)
    |--------------------------------------------------------------------------
    | Berguna agar link {{ route('dashboard') }} bisa tetap berfungsi di layout.
    */
    Route::get('/dashboard', function () {
        if (\Illuminate\Support\Facades\Auth::user() && \Illuminate\Support\Facades\Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('pelanggan.dashboard');
        }
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | ADMIN (Petugas)
    |--------------------------------------------------------------------------
    | Akses hanya untuk user dengan role = 'admin'
    | Menggunakan middleware IsAdmin secara langsung tanpa daftar di Kernel.php
    */
    Route::middleware([IsAdmin::class])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard');

        // CRUD Data Pelanggan
        Route::resource('/pelanggan', PelangganController::class);

        // CRUD Data Bulan
        Route::resource('/bulan', BulanController::class);

        // CRUD Data Tarif
        Route::resource('/tarif', TarifController::class);

        // CRUD Data Tagihan
        Route::resource('/tagihan', TagihanController::class);
        // Route::get('/tagihan/{id}/bayar', [TagihanController::class, 'bayar'])
        //     ->name('tagihan.bayar');
        Route::get('/get-data-pelanggan/{id}', [TagihanController::class, 'getDataPelanggan']);
        Route::get('tagihan/{tagihan}/bayar', [TagihanController::class, 'bayar'])->name('tagihan.bayar');
        Route::post('tagihan/{tagihan}/bayar', [TagihanController::class, 'prosesBayar'])->name('tagihan.prosesBayar');
        Route::get('tagihan/{tagihan}/cetak', [App\Http\Controllers\admin\TagihanController::class, 'cetakBukti'])->name('tagihan.cetakBukti');
        Route::get('/get-meter-awal/{pelanggan_id}', [TagihanController::class, 'getMeterAwal'])->name('getMeterAwal');

        Route::get('/informasi', [\App\Http\Controllers\admin\InformasiController::class, 'edit'])->name('informasi.edit');
        Route::post('/informasi', [\App\Http\Controllers\admin\InformasiController::class, 'update'])->name('informasi.update');
    });

    /*
    |--------------------------------------------------------------------------
    | PELANGGAN
    |--------------------------------------------------------------------------
    | Akses khusus pelanggan (role = 'pelanggan')
    */
    Route::middleware(['auth'])->group(function () {
        Route::prefix('pelanggan')->group(function () {
            Route::get('/dashboard', [PelangganDashboardController::class, 'index'])
                ->name('pelanggan.dashboard');
            Route::get('/tagihan-lunas', [PelangganDashboardController::class, 'tagihanLunas'])->name('pelanggan.tagihan_lunas');
            Route::get('/tagihan-belum-lunas', [PelangganDashboardController::class, 'tagihanBelumLunas'])->name('pelanggan.tagihan_belumlunas');
            Route::get('/pelanggan/tagihan/{tagihan}/cetak', [PelangganDashboardController::class, 'cetakTagihan'])
                ->name('pelanggan.cetak')
                ->middleware('auth');
        });
    });
});
