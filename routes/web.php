<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PembayaranSantriController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SantriController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\RuanganSantriController;
use App\Http\Controllers\PengeluaranBelanjaController;
use App\Models\PembayaranSantri;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/ceklogin', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');


//autentikasi login
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    //user controller
    Route::middleware(['akses'])->prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user');
        Route::post('/tambah', [UserController::class, 'tambah'])->name('user.tambah');
        Route::post('/edit', [UserController::class, 'edit'])->name('user.edit');
        Route::post('/hapus', [UserController::class, 'delete'])->name('user.hapus');
    });

    Route::prefix('santri')->group(function () {
        //santri controller
        Route::get('/', [SantriController::class, 'index'])->name('santri');
        Route::post('/tambah', [SantriController::class, 'tambah'])->name('santri.tambah');
        Route::post('/edit', [SantriController::class, 'edit'])->name('santri.edit');
        Route::post('/hapus', [SantriController::class, 'delete'])->name('santri.hapus');
    });

    Route::prefix('ruangan')->group(function () {
        //ruangan controller
        Route::get('/', [RuanganController::class, 'index'])->name('ruangan');
        Route::post('/tambah', [RuanganController::class, 'tambah'])->name('ruangan.tambah');
        Route::post('/edit', [RuanganController::class, 'edit'])->name('ruangan.edit');
        Route::post('/hapus', [RuanganController::class, 'delete'])->name('ruangan.hapus');
        Route::get('/santri/{id}', [RuanganSantriController::class, 'index']);
        Route::get('/santri/{id}/getSantri', [RuanganSantriController::class, 'getSantri']);
        Route::post('/santri/{id}/baru', [RuanganSantriController::class, 'baru']);
        Route::post('/santri/{id}/tambah', [RuanganSantriController::class, 'store']);
        Route::post('/santri/{id}/edit', [RuanganSantriController::class, 'edit']);
        Route::post('/santri/{id}/hapus', [RuanganSantriController::class, 'delete']);
    });
    Route::prefix(('pembayaran'))->group(function () {
        Route::get('/', [PembayaranController::class, 'index'])->name('pembayaran');
        Route::post('/tambah', [PembayaranController::class, 'tambah'])->name('pembayaran.tambah');
        Route::post('/edit', [PembayaranController::class, 'edit'])->name('pembayaran.edit');
        Route::post('/hapus', [PembayaranController::class, 'hapus'])->name('pembayaran.hapus');
    });

    Route::prefix('pengeluaranBelanja')->group(function () {
        //pengeluaran belanja controller
        Route::get('/', [PengeluaranBelanjaController::class, 'index'])->name('pengeluaranBelanja');
        Route::post('/tambah', [PengeluaranBelanjaController::class, 'tambah'])->name('pengeluaranBelanja.tambah');
        Route::post('/edit', [PengeluaranBelanjaController::class, 'edit'])->name('pengeluaranBelanja.edit');
        Route::post('/hapus', [PengeluaranBelanjaController::class, 'hapus'])->name('pengeluaranBelanja.hapus');
    });
});
