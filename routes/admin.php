<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\KegiatanController as AdminKegiatanController;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\GedungController;
use App\Http\Controllers\Admin\PeminjamanBarangController;
use App\Http\Controllers\Admin\QrCodeController;
use App\Http\Controllers\Admin\RuanganController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JadwalBarangController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\PeminjamanController;
use App\Http\Controllers\Admin\PengembalianController;
use App\Http\Controllers\Admin\ProdiController;
use App\Http\Controllers\Admin\ScanQRCodeController;
use App\Http\Controllers\Admin\UnitkerjaController;
use App\Http\Controllers\Admin\UserController;

// Route::get('/admin/dashboard', [DashboardController::class, 'index'])->middleware(['auth_admin'])->name('admin.dashboard');

// Scan QR Code Peminjaman Ruangan
Route::get('/scanPinjamRuangan', [ScanQRCodeController::class, 'index'])->middleware(['auth_baak'])->name('scan.pinjamRuangan');
Route::get('/scanPinjamRuangan/checkId/{id}', [ScanQRCodeController::class, 'checkId'])->middleware(['auth_baak'])->name('scan.pinjamRuangan.checkId');

// Scan QR Code Peminjaman Barang
Route::get('/scanPinjamBarang', [ScanQRCodeController::class, 'barang'])->middleware(['auth_baak'])->name('scan.pinjamBarang');
Route::get('/scanPinjamBarang/checkId/{id}', [ScanQRCodeController::class, 'checkIdBarang'])->middleware(['auth_baak'])->name('scan.pinjamBarang.checkId');

// ROUTE HANYA ADMIN BISA AKSES (auth_admin)
Route::middleware(['auth_admin'])->group(function () {
    // DATA MASTER
    Route::resource('/mahasiswa', MahasiswaController::class)->names('admin.mahasiswa');
    Route::resource('/master/prodi', ProdiController::class)->names('admin.prodi');
    Route::resource('/master/unit', UnitkerjaController::class)->names('admin.unit');
    Route::resource('/master/gedung', GedungController::class)->names('admin.gedung');
    Route::resource('/master/ruangan', RuanganController::class)->names('admin.ruangan');
    Route::resource('/master/barang', BarangController::class)->names('admin.barang');
    Route::resource('/master/kegiatan', AdminKegiatanController::class)->names('admin.kegiatan');
    Route::resource('/master/user', UserController::class)->names('admin.user');

    // EXPORT DATA BARANG KE EXCEL
    Route::get('barang/excel-export', [BarangController::class, 'exportExcel'])->name('excelExport');

    // LAPORAN PEMINJAMAN
    Route::get('/master/laporan', [PeminjamanController::class, 'laporan'])->name('admin.laporan');
    Route::get('/master/laporan/data', [PeminjamanController::class, 'fetchDataLaporan'])->name('fetch.data.laporan');
    Route::get('/master/laporan/cetak', [PeminjamanController::class, 'cetakLaporan'])->name('admin.cetak.laporan');

    // CETAK QRCODE BARANG (FILTER PER RUANGAN)
    Route::get('/filterBarang', [BarangController::class, 'filter'])->name('admin.barang.filter');
    Route::get('/filterBarang/{gedung_id}', [BarangController::class, 'ruanganByGedung'])->name('admin.barang.filterRuanganByGedung');
    Route::get('/CetakQrCode/{ruangan_id}', [BarangController::class, 'QrCode'])->name('admin.barang.QrCode');

    // MODAL DELETE PEMINJAMAN
    Route::get('/master/booking/modal-delete/{id}', [BookingController::class, 'modalDelete'])->name('admin.booking.modal-delete');
});

// ROUTE ADMIN DAN BAAK BISA AKSES (auth_baak)
Route::middleware(['auth_baak'])->group(function () {
    // DASHBOARD
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // JADWAL PEMINJAMAN (FULLCALENDAR)
    Route::get('/master/jadwal', [JadwalController::class, 'index'])->name('admin.jadwal');
    Route::get('/master/jadwal/filter', [JadwalController::class, 'filter'])->name('admin.jadwal.filter');

    // MENGELOLA LIST PEMINJAMAN/BOOKING
    Route::resource('/master/booking', BookingController::class)->names('admin.booking');
    Route::get('/master/booking/detail_barang/{id}', [BookingController::class, 'detail_barang'])->name('admin.booking.detail');
    Route::get('/master/booking/adminKonfirmasi/{id}', [BookingController::class, 'adminKonfirmasi'])->name('admin.booking.konfirmasi');

    // UNTUK AMBIL DATA USER PADA BAGIAN CREATE DAN EDIT PEMINJAMAN
    Route::get('get-user', [PeminjamanController::class, 'getUser'])->name('get.user');
    // MODAL PILIH APPROVER
    Route::get('/modal-approver', [PeminjamanController::class, 'modalApprover'])->name('admin.peminjaman.modal-approver');

    // UNTUK CREATE DAN EDIT PEMINJAMAN (ADMIN & BAAK)
    Route::resource('/master/peminjaman', PeminjamanController::class)->names('admin.peminjaman');

    // UNTUK UPDATE STATUS BARANG DAN RUANGAN DI PEMINJAMAN
    Route::put('/master/pengembalian/status_ruangan/{id}', [PengembalianController::class, 'status_ruangan'])->name('admin.pengembalian.status_ruangan');
    Route::resource('/master/pengembalian', PengembalianController::class)->names('admin.pengembalian');
});
