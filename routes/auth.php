<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Mahasiswa\BarangController as MahasiswaBarangController;
use App\Http\Controllers\Mahasiswa\BookingController;
use App\Http\Controllers\Mahasiswa\DetailpeminjamanController;
use App\Http\Controllers\Mahasiswa\PeminjamanBarangController;
use App\Http\Controllers\Mahasiswa\PeminjamanController;
use App\Http\Controllers\Mahasiswa\RuanganController;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', [EmailVerificationPromptController::class, '__invoke'])
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::get('/home', [HomeController::class, 'index'])->name('home');
});

Route::middleware(['auth_mahasiswa'])->group(function () {
    // Peminjaman
    Route::get('/peminjaman/list-peminjaman', [PeminjamanController::class, 'list_peminjaman'])->name('mahasiswa.peminjaman.list-peminjaman');
    Route::get('/peminjaman/detail_barang/{id}', [PeminjamanController::class, 'detail_barang'])->name('mahasiswa.peminjaman.detail');

    Route::get('/peminjaman/hapus-peminjaman/{id}', [PeminjamanController::class, 'confirmDelete'])->name('mahasiswa.peminjaman.delete');
    Route::Resource('/peminjaman', PeminjamanController::class)->names('mahasiswa.peminjaman');
    Route::get('/get-barang', [PeminjamanController::class, 'getBarang'])->name('get.barang');
    Route::get('/cetakPdf/{id}', [PeminjamanController::class, 'cetakPdf'])->name('mahasiswa.peminjaman.cetak');
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});


// (*Dibuat middleware 'auth' agar bisa juga diakses oleh admin dan baak)
Route::middleware(['auth'])->group(function () {
    // List Tabel Approver peminjaman
    Route::get('/list-approver', [PeminjamanController::class, 'listApprover'])->name('mahasiswa.peminjaman.list-approver');
    // Route Modal pilih/tambah Barang untuk form peminjaman 
    Route::get('/modal-barang', [PeminjamanController::class, 'modalBarang'])->name('mahasiswa.peminjaman.modal-barang');

    // Route cek ketersedian Barang untuk form peminjaman 
    Route::get('/cek-ketersediaan-barang', [PeminjamanController::class, 'cekKetersediaanBarang'])->name('cek.ketersediaan.barang');
    // Route cek ketersedian Ruangan untuk form peminjaman 
    Route::get('/cek-ketersediaan-ruangan', [PeminjamanController::class, 'cekKetersediaanRuangan'])->name('cek.ketersediaan.ruangan');
});
