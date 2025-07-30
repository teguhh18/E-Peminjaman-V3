<?php

use App\Http\Controllers\Admin\PeminjamanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Mahasiswa\BookingController;
use Illuminate\Support\Facades\Artisan;

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

Route::middleware(['auth'])->group(function () {
    Route::get('/perbarui-password', [HomeController::class, 'perbarui_password'])->name('perbarui_password');
    Route::post('/perbarui-password/updatepw', [HomeController::class, 'updatepw'])->name('perbaruipassword_new');
    Route::resource('/dashboard', HomeController::class)->names('home');

    Route::get('/profil', [HomeController::class, 'profil'])->name('profil');
    Route::put('/profil/update/{id}', [HomeController::class, 'profil_update'])->name('profil_update');
});

require __DIR__ . '/admin.php';
require __DIR__ . '/auth.php';
