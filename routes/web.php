<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SantriController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\RuanganSantriController;

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
        Route::get('/santri/{id}', [RuanganSantriController::class, 'index'])->name('ruangan.santri');
        Route::post('/santri/{id}/baru', [RuanganSantriController::class, 'baru'])->name('ruangan.santri.baru');
        Route::post('/santri/{id}/tambah', [RuanganSantriController::class, 'store'])->name('ruangan.santri.tambah');
        Route::post('/santri/{id}/edit', [RuanganSantriController::class, 'edit'])->name('ruangan.santri.edit');
        Route::post('/santri/{id}/hapus', [RuanganSantriController::class, 'delete'])->name('ruangan.santri.hapus');
    });
});
