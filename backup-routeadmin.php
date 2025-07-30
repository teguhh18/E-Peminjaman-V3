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
Route::get('/admin/dashboard', [DashboardController::class, 'index'])->middleware(['auth_baak'])->name('admin.dashboard');

Route::resource('/master/kegiatan', AdminKegiatanController::class)->middleware(['auth_admin'])->names('admin.kegiatan');
// Route::resource('/master/kegiatan', AdminKegiatanController::class)->middleware(['auth'])->names('admin.kegiatan');
Route::resource('/master/barang', BarangController::class)->middleware(['auth_admin'])->names('admin.barang');
Route::get('barang/excel-export', [BarangController::class, 'exportExcel'])->middleware(['auth_admin'])->name('excelExport');

Route::get('/CetakQrCode/{ruangan_id}', [BarangController::class, 'QrCode'])->middleware(['auth_admin'])->name('admin.barang.QrCode');

// Scan QR Code Peminjaman Ruangan
Route::get('/scanPinjamRuangan', [ScanQRCodeController::class, 'index'])->middleware(['auth_baak'])->name('scan.pinjamRuangan');
Route::get('/scanPinjamRuangan/checkId/{id}', [ScanQRCodeController::class, 'checkId'])->middleware(['auth_baak'])->name('scan.pinjamRuangan.checkId');

// Scan QR Code Peminjaman Barang
Route::get('/scanPinjamBarang', [ScanQRCodeController::class, 'barang'])->middleware(['auth_baak'])->name('scan.pinjamBarang');
Route::get('/scanPinjamBarang/checkId/{id}', [ScanQRCodeController::class, 'checkIdBarang'])->middleware(['auth_baak'])->name('scan.pinjamBarang.checkId');


Route::get('/filterBarang', [BarangController::class, 'filter'])->middleware(['auth_admin'])->name('admin.barang.filter');
Route::get('/filterBarang/{gedung_id}', [BarangController::class, 'ruanganByGedung'])->middleware(['auth_admin'])->name('admin.barang.filterRuanganByGedung');

Route::resource('/master/ruangan', RuanganController::class)->middleware(['auth_admin'])->names('admin.ruangan');
Route::resource('/master/gedung', GedungController::class)->middleware(['auth_admin'])->names('admin.gedung');

// Route::resource('/master/peminjaman', PeminjamanBarangController::class)->middleware(['auth_admin'])->names('admin.peminjaman');
// Route::get('master/peminjaman/ubah/{id}', [PeminjamanBarangController::class, 'ubah'])->middleware(['auth_admin'])->name('admin.peminjaman.ubah');

// Route pengelolaaann pengajuan Peminjaman/Booking
Route::get('/master/booking/detail_barang/{id}', [BookingController::class, 'detail_barang'])->middleware(['auth_baak'])->name('admin.booking.detail');
Route::get('/master/booking/adminKonfirmasi/{id}', [BookingController::class, 'adminKonfirmasi'])->middleware(['auth_baak'])->name('admin.booking.konfirmasi');
Route::get('/master/booking/modal-delete/{id}', [BookingController::class, 'modalDelete'])->middleware(['auth_baak'])->name('admin.booking.modal-delete');
Route::resource('/master/booking', BookingController::class)->middleware(['auth_baak'])->names('admin.booking');

// Route Pengembalian
Route::put('/master/pengembalian/status_ruangan/{id}', [PengembalianController::class, 'status_ruangan'])->middleware(['auth_baak'])->name('admin.pengembalian.status_ruangan');
Route::resource('/master/pengembalian', PengembalianController::class)->middleware(['auth_baak'])->names('admin.pengembalian');

// Route Admin Bypass Tambah Peminjaman
Route::get('/master/peminjaman/get-user', [PeminjamanController::class, 'getUser'])->name('get.user');
Route::get('/modal-approver', [PeminjamanController::class, 'modalApprover'])->name('admin.peminjaman.modal-approver')->middleware('auth_baak');
Route::resource('/master/peminjaman', PeminjamanController::class)->middleware(['auth_baak'])->names('admin.peminjaman');

// Laporan Peminjaman
Route::get('/master/laporan', [PeminjamanController::class, 'laporan'])->middleware(['auth_admin'])->name('admin.laporan');
Route::get('/master/laporan/data', [PeminjamanController::class, 'fetchDataLaporan'])->middleware('auth_admin')->name('fetch.data.laporan');
Route::get('/master/laporan/cetak', [PeminjamanController::class, 'cetakLaporan'])->middleware(['auth_admin'])->name('admin.cetak.laporan');

// Jadwal Peminjaman
Route::get('/master/jadwal', [JadwalController::class, 'index'])->middleware(['auth_baak'])->name('admin.jadwal');
Route::get('/master/jadwal/filter', [JadwalController::class, 'filter'])->middleware(['auth_baak'])->name('admin.jadwal.filter');


Route::resource('/master/user', UserController::class)->middleware(['auth_admin'])->names('admin.user');
Route::resource('/master/unit', UnitkerjaController::class)->middleware(['auth_admin'])->names('admin.unit');
Route::resource('/master/prodi', ProdiController::class)->middleware(['auth_admin'])->names('admin.prodi');

Route::resource('/mahasiswa', MahasiswaController::class)->middleware(['auth_admin'])->names('admin.mahasiswa');
