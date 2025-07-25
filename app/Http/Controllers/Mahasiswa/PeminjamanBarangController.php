<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Detailpeminjaman;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PeminjamanBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Peminjaman Barang";
        $user_id = auth()->user()->id;
        // $listPeminjaman = Peminjaman::with('detail_peminjaman.barang')->where('user_id', $user_id)->whereHas('detail_peminjaman')->get();
         $listPeminjaman = Peminjaman::with([
            'user',
            'detail_peminjaman.barang',

        ])
            ->where('user_id', $user_id)
            ->whereHas('detail_peminjaman')
            ->orderBy('id', 'desc')
            ->get();
        return view('mahasiswa.peminjaman-barang.index', compact('title', 'listPeminjaman'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Form Peminjaman';
        $barangs = Barang::all(); // ambil data barang
        return view('mahasiswa.peminjaman-barang.create', compact('title', 'barangs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kegiatan' => 'required',
            'no_peminjam' => 'required',
            'waktu_peminjaman' => 'required|date',
            'waktu_pengembalian' => 'required|date|after:waktu_peminjaman',
            'barang' => 'required|array',
            'barang.*.id' => 'required|exists:barangs,id',
        ]);

        $validatedData['user_id'] = auth()->user()->id;

        // Simpan ke tabel peminjaman
        $peminjaman = Peminjaman::create([
            'kegiatan' => $validatedData['kegiatan'],
            'no_peminjam' => $validatedData['no_peminjam'],
            'user_id' => $validatedData['user_id'],
            'waktu_peminjaman' => $validatedData['waktu_peminjaman'],
            'waktu_pengembalian' => $validatedData['waktu_pengembalian'],
        ]);

        // Simpan detail peminjaman
        foreach ($validatedData['barang'] as $barang) {
            \App\Models\DetailPeminjaman::create([
                'peminjaman_id' => $peminjaman->id, // isi dengan id peminjaman yang baru dibuat
                'barang_id' => $barang['id'],
                'jml_barang' => $barang['jumlah']
            ]);
        }
        return redirect()->route('mahasiswa.pinjam-barang.index')->with('success', 'Peminjaman berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Peminjaman  $peminjaman
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         $title = "Data Barang Peminjaman";
        $detailPeminjaman = Detailpeminjaman::with('barang', 'peminjaman_barang')
            ->where('peminjaman_id', $id)
            ->get();
        // dd($detailPeminjaman);
        $view =  view('mahasiswa.peminjaman-barang.detail-barang', compact(
            'title',
            'detailPeminjaman'
        ))->render();

        return response()->json([
            'success' => true,
            'html' => $view
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Peminjaman  $peminjaman
     * @return \Illuminate\Http\Response
     */
    public function edit(Peminjaman $peminjaman, $id)
    {
        $title = 'Edit Peminjaman';
        $barangs = Barang::all(); // ambil data barang

        // $peminjaman->load('detail_peminjaman.barang'); // load detail peminjaman
        $peminjaman = Peminjaman::with('detail_peminjaman.barang')->findOrFail(decrypt($id));
        return view('mahasiswa.peminjaman-barang.edit', compact('title', 'peminjaman', 'barangs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Peminjaman  $peminjaman
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $peminjaman = Peminjaman::where('id', decrypt($id))->first();
        $validatedData = $request->validate([
            'kegiatan' => 'required',
            'no_peminjam' => 'required',
            'waktu_peminjaman' => 'required|date',
            'waktu_pengembalian' => 'required|date|after:waktu_peminjaman',
            'barang' => 'required|array',
            'barang.*.id' => 'required|exists:barangs,id',

        ]);
        $validatedData['user_id'] = auth()->user()->id;
        // Update Peminjaman
        $peminjaman->update([
            'kegiatan' => $validatedData['kegiatan'],
            'no_peminjam' => $validatedData['no_peminjam'],
            'waktu_peminjaman' => $validatedData['waktu_peminjaman'],
            'waktu_pengembalian' => $validatedData['waktu_pengembalian'],
        ]);
        // dd($peminjaman);

        // hapus detail peminjaman yang ada
        $peminjaman->detail_peminjaman()->delete();
        // Simpan detail peminjaman yang baru
        foreach ($validatedData['barang'] as $barang) {
            Detailpeminjaman::create([
                'peminjaman_id' => $peminjaman->id,
                'barang_id' => $barang['id'],
                'jml_barang' => $barang['jumlah']
            ]);
        }
        return redirect()->route('mahasiswa.pinjam-barang.index')->with('msg', 'Peminjaman Berhasil Diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Peminjaman  $peminjaman
     * @return \Illuminate\Http\Response
     */
    public function destroy(Peminjaman $peminjaman, $id)
    {
        $dataPeminjaman = Peminjaman::find($id);
        $detail_peminjaman = Detailpeminjaman::where('peminjaman_id', $dataPeminjaman->id)->get();
        // dd($detail_peminjaman);
        $dataPeminjaman->delete();
        foreach ($detail_peminjaman as $detail) {
            $detail->delete();
        }

        return redirect()->route('mahasiswa.pinjam-barang.index')->with('success', 'Peminjaman Berhasil Dihapus');
    }

    public function dataBarang(Request $request)
    {
        $view = view('mahasiswa.peminjaman.tambah-barang')->render();

        return response()->json([
            'html' => $view
        ]);
    }
}
