<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SantriController;
use App\Http\Controllers\RuanganController;
use App\Models\Santri;

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
    Route::get('/user', [UserController::class, 'index'])->name('user');
    Route::post('/user/tambah', [UserController::class, 'tambah'])->name('user.tambah');
    Route::post('/user/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::post('/user/hapus', [UserController::class, 'delete'])->name('user.hapus');

    //santri controller
    Route::get('/santri', [SantriController::class, 'index'])->name('santri');
    Route::post('/santri/tambah', [SantriController::class, 'tambah'])->name('santri.tambah');
    Route::post('/santri/edit', [SantriController::class, 'edit'])->name('santri.edit');
    Route::post('/santri/hapus', [SantriController::class, 'delete'])->name('santri.hapus');

    //ruangan controller
    Route::get('/ruangan', [RuanganController::class, 'index'])->name('ruangan');
    Route::post('/ruangan/tambah', [RuanganController::class, 'tambah'])->name('ruangan.tambah');
    Route::post('/ruangan/edit', [RuanganController::class, 'edit'])->name('ruangan.edit');
    Route::post('/ruangan/hapus', [RuanganController::class, 'delete'])->name('ruangan.hapus');
});
