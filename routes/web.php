<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', [\App\Http\Controllers\AuthController::class, 'index'])->name('login');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('ceklogin');
Route::get('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/getMenu', [\App\Http\Controllers\DashboardController::class, 'getMenu'])->name('getmenu');

    Route::middleware(['roleAkses'])->group(function () {
        Route::prefix('role')->group(function () {
            Route::get('/', [\App\Http\Controllers\RoleController::class, 'index'])->name('role');
            Route::post('/aksi', [\App\Http\Controllers\RoleController::class, 'aksi'])->name('role.aksi');
        });
        Route::prefix('user')->group(function () {
            Route::get('/', [\App\Http\Controllers\UserController::class, 'index'])->name('user');
            Route::post('/aksi', [\App\Http\Controllers\UserController::class, 'aksi'])->name('user.aksi');
        });
        Route::prefix('menu')->group(function () {
            Route::get('/', [\App\Http\Controllers\MenuController::class, 'index'])->name('menu');
            Route::get('/submenu/{id}', [\App\Http\Controllers\SubmenuController::class, 'index'])->name('submenu');
            Route::post('/aksi', [\App\Http\Controllers\MenuController::class, 'aksi'])->name('menu.aksi');
            Route::post('/submenu/{id}/aksi', [\App\Http\Controllers\SubmenuController::class, 'aksi'])->name('submenu.aksi');
        });
        Route::prefix('akses')->group(function () {
            Route::get('/', [\App\Http\Controllers\AksesController::class, 'index'])->name('akses');
            Route::get('/edit/{id}', [\App\Http\Controllers\AksesController::class, 'edit'])->name('akses.edit');
            Route::post('/check/{id}', [\App\Http\Controllers\AksesController::class, 'check'])->name('akses.check');
        });
        Route::prefix('opd')->group(function () {
            Route::get('/', [\App\Http\Controllers\OpdController::class, 'index'])->name('opd');
            Route::post('/aksi', [\App\Http\Controllers\OpdController::class, 'aksi'])->name('opd.aksi');
        });
        Route::prefix('jabatan')->group(function () {
            Route::get('/', [\App\Http\Controllers\JabatanController::class, 'index'])->name('jabatan');
            Route::post('/aksi', [\App\Http\Controllers\JabatanController::class, 'aksi'])->name('jabatan.aksi');
        });
        Route::prefix('pegawai')->group(function () {
            Route::get('/', [\App\Http\Controllers\PegawaiController::class, 'index'])->name('pegawai');
            Route::post('/aksi', [\App\Http\Controllers\PegawaiController::class, 'aksi'])->name('pegawai.aksi');
            Route::get('/detail/{id}', [\App\Http\Controllers\PegawaiController::class, 'detail'])->name('pegawai.detail');
        });
    });
});
