<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Detailpeminjaman;
use App\Models\Peminjaman;
use App\Models\PeminjamanRuangan;
use App\Models\PersetujuanPeminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        $title = "Data Booking";
        $user = Auth::user();

        if ($user->level == 'admin' || $user->level == 'kerumahtanggaan') {

            //Ambil semua peminjaman
            $dataBooking = Peminjaman::with(['user', 'ruangan.gedung', 'persetujuan_peminjaman'])->whereNotIn('status_peminjaman', ['selesai', 'ditolak'])
                ->orderBy('id', 'desc')
                ->get();
            // dd($dataBooking);

        } else {
            // Ambil peminjaman yang memiliki persetujuan untuk unit kerja user yang login
            $dataBooking = Peminjaman::whereNotIn('status_peminjaman', ['selesai', 'ditolak'])
                ->whereHas('persetujuan_peminjaman', function ($query) use ($user) {
                    $query->where('unitkerja_id', $user->unitkerja_id);
                })
                ->with(['user', 'ruangan.gedung', 'persetujuan_peminjaman'])
                ->orderBy('id', 'desc')
                ->get();
        }

        // dd($dataBooking);
        return view('admin.booking.data', compact(
            'title',
            'dataBooking'
        ));
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
     * @param  \App\Models\PeminjamanRuangan  $peminjamanRuangan
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        // dd($id);
        $title = "Konfirmasi Peminjaman";
        $dataPinjam = PersetujuanPeminjaman::where("id", $id)->first();
        $view = view('admin.booking.confirm', compact('title', 'dataPinjam'))->render();
        return response()->json([
            'success' => true,
            'html' => $view
        ]);
    }

    public function adminKonfirmasi(Request $request, $id)
    {
        // dd($id);
        $title = "Konfirmasi Peminjaman";
        $dataPinjam = PersetujuanPeminjaman::where("id", $id)->first();
        $view = view('admin.booking.confirm', compact('title', 'dataPinjam'))->render();
        return response()->json([
            'success' => true,
            'html' => $view
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PeminjamanRuangan  $peminjamanRuangan
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $title = "Edit Peminjaman";
        $dataPinjam = Peminjaman::where("id", decrypt($id))->first();
        // return view('admin.booking.update', compact('title', 'dataPinjam'))->render();
        return view('admin.booking.update', compact(
            'title',
            'dataPinjam'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PeminjamanRuangan  $peminjamanRuangan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request);
        // Langkah 1: Update status persetujuan yang spesifik
        $persetujuan = PersetujuanPeminjaman::findOrFail($id);
        $persetujuan->update([
            'status' => $request->status,
            'approver_id' => auth()->id(), // Simpan siapa yang melakukan aksi
            'tanggal_aksi' => now(),      // Simpan kapan aksi dilakukan
            'catatan' => $request->catatan, // Simpan catatan jika ada
        ]);
        // Dapatkan Peminjaman induknya
        $peminjaman = $persetujuan->peminjaman;

        // Langkah 2: Ambil semua status persetujuan untuk peminjaman ini
        $semuaPersetujuan = $peminjaman->persetujuan_peminjaman;
        // dd($semuaPersetujuan);

        // Langkah 3: Cek kondisi dan perbarui status peminjaman utama
        $adaYangMenolak = $semuaPersetujuan->contains('status', 'ditolak');

        if ($adaYangMenolak) {
            // Jika salah satu saja ada yang menolak, langsung update status utama menjadi 'ditolak'
            $peminjaman->update(['status_peminjaman' => 'ditolak']);
        } else {
            // Jika tidak ada yang menolak, cek apakah semua sudah menyetujui
            $semuaMenyetujui = $semuaPersetujuan->every(function ($value, $key) {
                return $value->status === 'disetujui';
            });
            if ($semuaMenyetujui) {
                // Jika semua sudah setuju, update status utama menjadi 'disetujui'
                $peminjaman->update(['status_peminjaman' => 'disetujui']); // 2 = Disetujui

                // 1. Update status untuk ruangan jika ada yang dipinjam
                if ($peminjaman->ruangan_id) {
                    $peminjaman->update(['status_ruangan' => 'disetujui']);
                }

                // 2. Update status untuk semua barang yang dipinjam
                // Pastikan relasi di Model Peminjaman bernama 'detail_peminjaman'
                $peminjaman->detail_peminjaman()->update(['status' => 'disetujui']);
            }
        }

        // Langkah 4: Redirect kembali dengan pesan sukses
        return redirect()->route('admin.booking.index')->with(['msg' => 'Status persetujuan berhasil diperbarui', 'class' => 'alert-success']);
    }

    public function detail_barang($id)
    {
        $title = "Peminjaman Detail Barang";
        // Ambil data peminjaman utama
        $peminjaman = Peminjaman::findOrFail($id);

        // Ambil detail barangnya melalui relasi
        $detailPeminjaman = $peminjaman->detail_peminjaman()->with('barang.ruangan.gedung', 'barang.ruangan.unitkerja')->get();
        // $detailPeminjaman = Detailpeminjaman::with('barang', 'peminjaman')
        //     ->where('peminjaman_id', $id)
        //     ->get();
        // dd($detailPeminjaman);
        $view =  view('admin.booking.detail-barang', compact(
            'title',
            'peminjaman',
            'detailPeminjaman'
        ))->render();
        return response()->json([
            'success' => true,
            'html' => $view
        ]);
    }

    public function modalDelete($id)
    {
        $title = "Hapus Peminjaman";
        $peminjaman = Peminjaman::where('id', $id)->first();
        $view =  view('admin.booking.modal-delete', compact(
            'title',
            'peminjaman',
        ))->render();
        return response()->json([
            'success' => true,
            'html' => $view
        ]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PeminjamanRuangan  $peminjamanRuangan
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Peminjaman::where("id", $id)->delete();
        return back()->with(['msg' => 'Berhasil Menghapus', 'class' => 'alert-success']);
    }
}
