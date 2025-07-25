<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\PeminjamanRuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScanQRCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    

    public function index()
    {
        $title = "Scan Pinjam Ruangan";

        return view('admin.scanQR.scan-pinjam-ruangan', compact(
            'title'
        ));
    }

    public function checkId($id)
    {
        $bookingExists = PeminjamanRuangan::where('id', decrypt($id))->where('status', 2)->exists();
        // dd($bookingExists);
        if ($bookingExists) {
            return response()->json(['exists' => true]);
        } else {
            return response()->json(['exists' => false]);
        }
    }

    public function barang()
    {
        $title = "Scan Pinjam Barang";

        return view('admin.scanQR.scan-pinjam-barang', compact(
            'title'
        ));
    }

    public function checkIdBarang($id)
    {
        $pinjamExists = Peminjaman::where('id', decrypt($id))->where('konfirmasi', 2)->exists();

        if ($pinjamExists) {
            return response()->json(['exists' => true]);
        } else {
            return response()->json(['exists' => false]);
        }
    }
}
