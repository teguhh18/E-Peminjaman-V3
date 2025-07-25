<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Detailpeminjaman;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PengembalianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $title = "Ubah Status Ruangan";
        $statusRuangan = Peminjaman::where('id', $id)->firstOrFail();
        $view = view('admin.booking.status-ruangan', compact('title', 'statusRuangan'))->render();
        return response()->json([
            'success' => true,
            'html' => $view
        ]);
    }

    public function status_ruangan(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // Update status ruangan
        $peminjaman->update([
            'status_ruangan' => $request->status,
            // 'catatan' => $request->catatan,
        ]);

        // --- Logika kondisional berdasarkan status baru ---

        // Jika status diubah menjadi "kunci_diambil"
        if ($request->status === 'kunci_diambil') {
            // Hanya ubah status utama jika statusnya masih 'Disetujui' (2)
            if ($peminjaman->konfirmasi == 'disetujui') {
                $peminjaman->update(['status_peminjaman' => 'aktif']); // 4 = Aktif/Sedang Dipinjam
            }
        }
        // Jika status diubah menjadi "kunci_dikembalikan"
        elseif ($request->status === 'kunci_dikembalikan') {
            // Panggil fungsi bantuan untuk mengecek apakah seluruh peminjaman sudah selesai
            $this->cekDanSelesaikanPeminjaman($peminjaman);
        }
        // Jika status "bermasalah", tidak ada aksi pada status utama.

        return back()->with('success', 'Status ruangan berhasil diperbarui.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $title = "Ubah Status Barang";
        $statusBarang = Detailpeminjaman::where('id', $id)->firstOrFail();
        $view = view('admin.booking.status-barang', compact('title', 'statusBarang'))->render();
        return response()->json([
            'success' => true,
            'html' => $view
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $detailBarang = Detailpeminjaman::findOrFail($id);
        $peminjaman = $detailBarang->peminjaman; // Ambil data peminjaman induk di awal

        // Update status barang
        $detailBarang->update([
            'status' => $request->status,
            // 'catatan' => $request->catatan,
        ]);

        // =======================================================
        // LOGIKA BERDASARKAN STATUS BARU
        // =======================================================

        // --- Jika status diubah menjadi "diambil" ---
        if ($request->status === 'diambil') {
            // Hanya ubah status utama jika statusnya masih 'Disetujui' (2)
            if ($peminjaman->konfirmasi == 'disetujui') {
                $peminjaman->update(['status_peminjaman' => 'aktif']); // 4 = Sedang Dipinjam/Aktif
            }
        }

        // --- Jika status diubah menjadi "dikembalikan" ---
        if ($request->status === 'dikembalikan') {
            // Panggil fungsi bantuan untuk mengecek apakah semua aset sudah kembali
            $this->cekDanSelesaikanPeminjaman($peminjaman);
        }

        // --- Jika status diubah menjadi "bermasalah" ---
        // Tidak ada aksi khusus yang perlu dilakukan pada status peminjaman utama.
        // Peminjaman akan tetap aktif sampai masalah diselesaikan dan status diubah menjadi "dikembalikan".

        return back()->with('success', 'Status barang berhasil diperbarui.');
    }


    /**
     * Method private untuk mengecek dan menyelesaikan peminjaman secara keseluruhan.
     *
     * @param \App\Models\Peminjaman $peminjaman
     * @return void
     */
    private function cekDanSelesaikanPeminjaman(Peminjaman $peminjaman)
    {
        // Muat ulang relasi untuk mendapatkan data terbaru dari database
        $peminjaman->load('detail_peminjaman');

        // Kondisi 1: Cek status ruangan
        // Dianggap selesai jika sudah dikembalikan ATAU jika memang tidak pinjam ruangan.
        $ruanganSelesai = is_null($peminjaman->ruangan_id) || $peminjaman->status_ruangan === 'kunci_dikembalikan';

        // Kondisi 2: Cek status semua barang
        // Dianggap selesai jika tidak ada lagi barang yang statusnya BUKAN 'dikembalikan'.
        $barangSelesai = $peminjaman->detail_peminjaman->where('status', '!=', 'dikembalikan')->isEmpty();

        // Jika KEDUA kondisi terpenuhi, maka peminjaman selesai total.
        if ($ruanganSelesai && $barangSelesai) {
            $peminjaman->update(['status_peminjaman' => 'selesai']); // 5 = Selesai
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
