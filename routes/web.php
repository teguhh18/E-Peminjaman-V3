<?php

use App\Http\Controllers\Admin\PeminjamanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Mahasiswa\BookingController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Mahasiswa\PeminjamanController As MahasiswaPeminjamanController;
use App\Http\Controllers\Mahasiswa\RuanganController;

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



Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    return "Cache cleared successfully";
});
Route::get('/not-found', function () {
    return view('not-found');
});

Route::get('/', function () {
    return redirect('login');
});

Route::get('/perbarui-password', [HomeController::class, 'perbarui_password'])->middleware(['auth'])->name('perbarui_password');
Route::post('/perbarui-password/updatepw', [HomeController::class, 'updatepw'])->middleware(['auth'])->name('perbaruipassword_new');
Route::resource('/dashboard', HomeController::class)->middleware(['auth'])->names('home');

// Cetak PDF
Route::get('/CetakPeminjaman/{id}', [MahasiswaPeminjamanController::class, 'PrintPdf'])->middleware(['auth'])->name('mahasiswa.peminjaman.cetak');
Route::get('/CetakBooking/{id}', [BookingController::class, 'PrintPdf'])->middleware(['auth'])->name('mahasiswa.booking.cetak');


Route::get('/ruangan/listPeminjaman', [RuanganController::class, 'list_peminjaman'])->middleware('auth_mahasiswa')->name('ruangan.list');
// Route::get('/ruangan/ubahPeminjaman/{id}', [RuanganController::class, 'ubah_peminjaman'])->middleware('auth_mahasiswa')->name('mahasiswa.ruangan.ubah');


Route::get('/profil', [HomeController::class, 'profil'])->middleware('auth')->name('profil');
Route::put('/profil/update/{id}', [HomeController::class, 'profil_update'])->middleware('auth')->name('profil_update');



require __DIR__ . '/admin.php';
require __DIR__ . '/auth.php';
