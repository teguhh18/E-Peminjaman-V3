<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Detailpeminjaman;
use Illuminate\Http\Request;

class DetailpeminjamanController extends Controller
{
    public function index()
    {
        $dataBarang = Barang::all();
        $view = view('mahasiswa.aset.modalcart', compact('dataBarang'))->render();
        return response()->json([
            'success' => true,
            'html' => $view
        ]);
    }

    public function store(Request $request)
    {
        $dataValid =  $request->validate([
            'pinjambarang_id' => 'required',
            'barang_id' => 'required',
            'jml_barang' => 'required',

        ]);

        // dd($dataValid);
        Detailpeminjaman::create($dataValid);

        return back()->with(['msg' => 'Berhasil Menambah Data', 'class' => 'alert-success']);
        // return redirect('/peminjaman/' . $$request->pinjambarang_id)->with('success', 'Berhasil menambahkan barang');
    }
}
