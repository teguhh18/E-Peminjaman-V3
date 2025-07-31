<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse; // Tambahkan di bagian atas controller
use Illuminate\Support\Facades\Log;


class DashboardController extends Controller
{
    public function index()
    {
        $count_title = "Total Peminjaman";
        $count = Peminjaman::count();

        $count_barang = Barang::count();
        $count_ruangan = Ruangan::count();

        return view('admin.home.index', compact('count_title', 'count','count_barang','count_ruangan'));
    }


    public function filter_status(Request $request): JsonResponse
    {
          Log::info('Filter status called', $request->all());
        // dd($request);
        try {
            // Ambil status, jika tidak ada, defaultnya adalah 'semua'
            $status = $request->input('status', 'semua');

            // Gunakan when() untuk query kondisional yang lebih bersih
            $count = Peminjaman::query()
                ->when($status !== 'semua', function ($query) use ($status) {
                    return $query->where('status_peminjaman', $status);
                })
                ->count();

            // Buat judul yang lebih rapi
            $count_title = ($status === 'semua')
                ? 'semua'
                : Str::ucfirst($status);

            return response()->json([
                'count_title' => $count_title,
                'count' => $count,
            ]);
        } catch (\Exception $e) {
            // Jika terjadi error, kirim respons error yang jelas
            return response()->json(['message' => 'Gagal memuat data'], 500);
        }
    }
}
