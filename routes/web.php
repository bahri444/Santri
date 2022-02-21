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
        Route::prefix('santri')->group(function () {
            Route::get('/', [\App\Http\Controllers\SantriController::class, 'index'])->name('santri');
            Route::post('/aksi', [\App\Http\Controllers\SantriController::class, 'aksi'])->name('santri.aksi');
            Route::get('/detail/{id}', [\App\Http\Controllers\SantriController::class, 'detail'])->name('santri.detail');
        });
        Route::prefix('ruangan')->group(function () {
            Route::get('/', [\App\Http\Controllers\RuanganController::class, 'index'])->name('ruangan');
            Route::post('/aksi', [\App\Http\Controllers\RuanganController::class, 'aksi'])->name('ruangan.aksi');
            Route::get('/detail/{id}', [\App\Http\Controllers\RuanganController::class, 'detail'])->name('ruangan.detail');
            Route::get('/{id}/santri', [\App\Http\Controllers\RuanganSantriController::class, 'index'])->name('ruangan.santri');
            Route::post('/{id}/santri/aksi', [\App\Http\Controllers\RuanganSantriController::class, 'aksi'])->name('ruangan.santri.aksi');
            Route::get('/{id}/getSantri', [\App\Http\Controllers\RuanganSantriController::class, 'getSantri'])->name('ruangan.santri.getSantri');
            Route::get('/{id}/getRuangan', [\App\Http\Controllers\RuanganSantriController::class, 'getRuangan'])->name('ruangan.santri.getRuangan');
        });
        Route::prefix('tagihan')->group(function () {
            Route::get('/', [\App\Http\Controllers\TagihanController::class, 'index'])->name('tagihan');
            Route::post('/aksi', [\App\Http\Controllers\TagihanController::class, 'aksi'])->name('tagihan.aksi');
        });
        Route::prefix('pembayaran')->group(function () {
            Route::get('/', [\App\Http\Controllers\PembayaranController::class, 'index'])->name('pembayaran');
            Route::post('/aksi', [\App\Http\Controllers\PembayaranController::class, 'aksi'])->name('pembayaran.aksi');
            Route::post('/detail/{id}', [\App\Http\Controllers\PembayaranController::class, 'detail'])->name('pembayaran.detail');
        });
        Route::prefix('laporan')->group(function () {
            Route::get('/', [\App\Http\Controllers\LaporanController::class, 'index'])->name('laporan');
            Route::post('/datatable', [\App\Http\Controllers\LaporanController::class, 'datatable'])->name('laporan.datatable');
            Route::post('/aksi', [\App\Http\Controllers\LaporanController::class, 'aksi'])->name('laporan.aksi');
        });
        Route::prefix('pengeluaran')->group(function () {
            Route::get('/', [\App\Http\Controllers\PengeluaranController::class, 'index'])->name('pengeluaran');
            Route::post('/aksi', [\App\Http\Controllers\PengeluaranController::class, 'aksi'])->name('pengeluaran.aksi');
        });
        Route::prefix('setting')->group(function () {
            Route::get('/', [\App\Http\Controllers\SettingController::class, 'index'])->name('setting');
            Route::post('/simpan', [\App\Http\Controllers\SettingController::class, 'simpan'])->name('setting.simpan');
        });
    });
});
