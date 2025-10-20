<?php

use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsPelanggan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\admin\BulanController;
use App\Http\Controllers\admin\TarifController;
use App\Http\Controllers\admin\TagihanController;
use App\Http\Controllers\admin\PelangganController;
use App\Http\Controllers\admin\PengaduanController; // DITAMBAHKAN
use App\Http\Controllers\admin\DashboardController as AdminDashboard;
use App\Http\Controllers\pelanggan\PelangganDashboardController;
use App\Http\Controllers\Pelanggan\PengaduanPelangganController; // DITAMBAHKAN

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
    Route::middleware([IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // CRUD Data Pelanggan
        Route::resource('/pelanggan', PelangganController::class);

        // CRUD Data Bulan
        Route::resource('/bulan', BulanController::class);

        // CRUD Data Tarif
        Route::resource('/tarif', TarifController::class);

        // CRUD Data Tagihan
        Route::resource('/tagihan', TagihanController::class);
        Route::get('/get-data-pelanggan/{id}', [TagihanController::class, 'getDataPelanggan']);
        Route::get('tagihan/{tagihan}/bayar', [TagihanController::class, 'bayar'])->name('tagihan.bayar');
        Route::post('tagihan/{tagihan}/bayar', [TagihanController::class, 'prosesBayar'])->name('tagihan.prosesBayar');
        Route::get('tagihan/{tagihan}/cetak', [App\Http\Controllers\admin\TagihanController::class, 'cetakBukti'])->name('tagihan.cetakBukti');
        Route::get('/get-meter-awal/{pelanggan_id}', [TagihanController::class, 'getMeterAwal'])->name('getMeterAwal');

        Route::get('/informasi', [\App\Http\Controllers\admin\InformasiController::class, 'edit'])->name('informasi.edit');
        Route::post('/informasi', [\App\Http\Controllers\admin\InformasiController::class, 'update'])->name('informasi.update');

        // --- Fitur Manajemen Pengaduan untuk Admin --- DITAMBAHKAN
        Route::prefix('pengaduan')->name('pengaduan.')->group(function () {
            Route::get('/', [PengaduanController::class, 'index'])->name('index');
            Route::get('/{pengaduan}', [PengaduanController::class, 'show'])->name('show');
            Route::put('/{pengaduan}', [PengaduanController::class, 'tanggapi'])->name('tanggapi');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | PELANGGAN
    |--------------------------------------------------------------------------
    | Akses khusus pelanggan (role = 'pelanggan')
    */
    Route::middleware(['auth'])->group(function () {
        Route::prefix('pelanggan')->name('pelanggan.')->group(function () {
            Route::get('/dashboard', [PelangganDashboardController::class, 'index'])
                ->name('dashboard');
            Route::get('/tagihan-lunas', [PelangganDashboardController::class, 'tagihanLunas'])->name('tagihan_lunas');
            Route::get('/tagihan-belum-lunas', [PelangganDashboardController::class, 'tagihanBelumLunas'])->name('tagihan_belumlunas');
            Route::get('/tagihan/{tagihan}/cetak', [PelangganDashboardController::class, 'cetakTagihan'])
                ->name('cetak')
                ->middleware('auth');

            // --- Fitur Pengaduan untuk Pelanggan --- DITAMBAHKAN
            Route::prefix('pengaduan')->name('pengaduan.')->group(function () {
                Route::get('/', [PengaduanPelangganController::class, 'index'])->name('index');
                Route::get('/create', [PengaduanPelangganController::class, 'create'])->name('create');
                Route::post('/', [PengaduanPelangganController::class, 'store'])->name('store');
                Route::get('/{pengaduan}', [PengaduanPelangganController::class, 'show'])->name('show');
                Route::put('/{pengaduan}/selesaikan', [PengaduanPelangganController::class, 'selesaikan'])->name('selesaikan');
            });
        });
    });
});

