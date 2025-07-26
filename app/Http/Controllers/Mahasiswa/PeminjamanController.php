<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Detailpeminjaman;
use App\Models\Peminjaman;
use App\Models\PersetujuanPeminjaman;
use App\Models\Ruangan;
use App\Models\Unitkerja;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Riwayat Peminjaman';


        return view('mahasiswa.peminjaman.index', compact('title'));
    }

    public function list_peminjaman()
    {
        $id = auth()->user()->id;
        $dataRuangan = Ruangan::with('gedung')->get();

        $dataPeminjaman = Peminjaman::with([
            'user',
            'detail_peminjaman.barang',
            'ruangan'
        ])
            ->where('user_id', $id)
            ->orderBy('id', 'desc')
            ->get();


        // dd($dataPeminjaman);
        $view = view('mahasiswa.peminjaman.list_peminjaman', compact('dataPeminjaman'))->render();
        return response()->json([
            'success' => true,
            'html' => $view
        ]);
    }

    public function create()
    {
        $user = User::where('id', auth()->user()->id)->first();
        $title = "Buat Peminjaman";
        $dataRuangan = Ruangan::with('gedung')->where('bisa_pinjam', 1)->get();
        // dd($dataRuangan);
        $dataBarang = Ruangan::with('ruangan')->get();
        return view('mahasiswa.peminjaman.create', compact('title', 'user', 'dataRuangan', 'dataBarang'))->render();
    }


    public function store(Request $request)
    {
        // 1. Validasi Awal untuk data yang selalu ada
        $request->validate([
            'kegiatan'           => 'required|string|max:255',
            'waktu_peminjaman'   => 'required|date',
            'waktu_pengembalian' => 'required|date|after_or_equal:waktu_peminjaman',
            'no_telepon'   => 'required',
        ]);

        // 2. Cek apakah minimal ada satu item (ruangan atau barang) yang dipilih
        if (!$request->filled('ruangan_id') && !$request->has('barang_id')) {
            return back()->with('msg', 'Anda harus memilih minimal satu ruangan atau satu barang untuk dipinjam.');
        }

        // 3. Mulai Transaksi Database
        // memastikan semua data berhasil disimpan atau tidak sama sekali.
        try {
            DB::beginTransaction();

            // 4. Siapkan Data untuk Tabel Peminjaman Utama
            $dataPeminjaman = [
                'user_id'            => auth()->id(),
                'kegiatan'           => $request->kegiatan,
                'no_peminjam'        => $request->no_telepon,
                'waktu_peminjaman'   => date('Y-m-d H:i:s', strtotime($request->waktu_peminjaman)),
                'waktu_pengembalian' => date('Y-m-d H:i:s', strtotime($request->waktu_pengembalian)),
                'ruangan_id'         => $request->ruangan_id,
            ];

            // Buat data peminjaman utama
            $peminjamanBaru = Peminjaman::create($dataPeminjaman);

            // 5. Validasi dan Simpan Detail Barang (jika ada)
            $unitKerjaIds = []; // Array untuk menampung ID unit kerja dari aset yang dipinjam

            if ($request->has('barang_id') && is_array($request->barang_id)) {
                $request->validate([
                    'barang_id.*'       => 'required|exists:barangs,id',
                    'jumlah_barang.*'   => 'required|integer|min:1',
                ]);

                foreach ($request->barang_id as $key => $barangId) {
                    if (!empty($barangId)) {
                        Detailpeminjaman::create([
                            'peminjaman_id' => $peminjamanBaru->id,
                            'barang_id'     => $barangId,
                            'jml_barang'    => $request->jumlah_barang[$key] ?? 1,
                        ]);
                        // Kumpulkan unit kerja dari setiap barang
                        $barang = Barang::find($barangId);
                        if ($barang && $barang->ruangan->unitkerja_id) {
                            $unitKerjaIds[] = $barang->ruangan->unitkerja_id;
                        }
                    }
                }
            }

            // 6. Kumpulkan unit kerja dari ruangan (jika ada)
            if ($request->filled('ruangan_id')) {
                $request->validate(['ruangan_id' => 'exists:ruangans,id']);
                $ruangan = Ruangan::find($request->ruangan_id);
                if ($ruangan && $ruangan->unitkerja_id) {
                    $unitKerjaIds[] = $ruangan->unitkerja_id;
                }
            }

            // 7. BUAT DATA PERSETUJUAN

            // Persetujuan Kerumahtanggaan (Selalu ada)
            // Ambil data untuk mengisi id unit kerja Kerumahtanggan
            $kerumahtanggan = User::where('level', 'kerumahtanggaan')->firstOrFail();
            PersetujuanPeminjaman::create([
                'peminjaman_id' => $peminjamanBaru->id,
                'status'        => 'menunggu',
                'unitkerja_id'  => $kerumahtanggan->unitkerja_id
            ]);

            // Persetujuan BAAK (Berdasarkan unit kerja aset yang dipinjam, unik)
            $uniqueUnitKerjaIds = array_unique($unitKerjaIds);
            foreach ($uniqueUnitKerjaIds as $unitKerjaId) {
                PersetujuanPeminjaman::create([
                    'peminjaman_id' => $peminjamanBaru->id,
                    // Anda mungkin perlu menyimpan target unit kerja di sini jika ada beberapa BAAK per unit
                    'unitkerja_id' => $unitKerjaId,
                    'status'        => 'menunggu'
                ]);
            }

            // Jika semua proses di atas berhasil, commit transaksi
            DB::commit();

            return redirect()->route('mahasiswa.peminjaman.index')->with('msg', 'Berhasil Membuat Pengajuan Peminjaman');
        } catch (\Exception $e) {
            // Jika ada error di mana pun dalam blok 'try', batalkan semua
            DB::rollBack();

            Log::error('Gagal menyimpan peminjaman: ' . $e->getMessage());

            return back()->with('msg', 'Terjadi kesalahan. Gagal Membuat Pengajuan Peminjaman.')->withInput();
        }
    }


    public function show($id)
    {
        $persetujuan = PersetujuanPeminjaman::where('peminjaman_id', decrypt($id))->get();
        $view =  view('mahasiswa.peminjaman.konfirmasi', compact('persetujuan'))->render();
        return response()->json([
            'success' => true,
            'html' => $view
        ]);
    }

    public function cekKetersediaanRuangan(Request $request)
    {
        $start = Carbon::parse($request->waktu_peminjaman);
        $end = Carbon::parse($request->waktu_pengembalian);

        $exists = Peminjaman::where('ruangan_id', $request->ruangan_id)
            ->where('status_peminjaman', 'disetujui') // peminjaan yang sudah disetujui
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('waktu_peminjaman', [$start, $end])
                    ->orWhereBetween('waktu_pengembalian', [$start, $end])
                    ->orWhere(function ($q2) use ($start, $end) {
                        $q2->where('waktu_peminjaman', '<=', $start)
                            ->where('waktu_pengembalian', '>=', $end);
                    });
            })->exists();

        return response()->json(['available' => $exists]);
    }

    public function modalBarang()
    {
        $dataBarang = Barang::with('ruangan')->where('jumlah', '>', 0)->get();
        $view =  view('mahasiswa.peminjaman.tambah-barang', compact('dataBarang'))->render();
        return response()->json([
            'success' => true,
            'html' => $view
        ]);
    }

    public function cekKetersediaanBarang(Request $request)
    {
        $request->validate([
            'barang_id'          => 'required|integer|exists:barangs,id',
            'waktu_peminjaman'   => 'required|date',
            'waktu_pengembalian' => 'required|date',
        ]);

        $barangId = $request->barang_id;
        $waktuMulai = date('Y-m-d H:i:s', strtotime($request->waktu_peminjaman));
        $waktuSelesai = date('Y-m-d H:i:s', strtotime($request->waktu_pengembalian));

        // 1. Ambil total stok fisik dari barang
        $barang = Barang::findOrFail($barangId);
        $totalStokFisik = $barang->jumlah;

        // 2. Hitung jumlah barang yang sudah dipinjam di waktu yang tumpang tindih
        $jumlahDipesan = Detailpeminjaman::where('barang_id', $barangId)
            ->whereHas('peminjaman', function ($query) use ($waktuMulai, $waktuSelesai) {
                $query
                    // Hanya hitung peminjaman yang sudah pasti (disetujui/aktif)
                    ->whereIn('status_peminjaman', ['disetujui', 'aktif'])
                    // Kondisi tumpang tindih (overlap)
                    ->where(function ($q) use ($waktuMulai, $waktuSelesai) {
                        $q->where('waktu_peminjaman', '<', $waktuSelesai)
                            ->where('waktu_pengembalian', '>', $waktuMulai);
                    });
            })
            ->sum('jml_barang');

        // 3. Hitung stok yang tersedia
        $stokTersedia = $totalStokFisik - $jumlahDipesan;

        return response()->json([
            'stok_tersedia' => $stokTersedia,
        ]);
    }


    public function getBarang(Request $request)
    {
        $barang = Barang::find($request->id);
        return response()->json($barang);
    }

    public function listApprover(Request $request)
    {

        $unitKerjaIds = [];

        // Blok ini hanya berjalan jika ruangan_id ada isinya
        if ($request->filled('ruangan_id')) {
            $ruangan = Ruangan::find($request->ruangan_id);
            if ($ruangan && $ruangan->unitkerja_id) {
                $unitKerjaIds[] = $ruangan->unitkerja_id;
            }
        }

        // Blok ini hanya berjalan jika ada barang_id yang dikirim
        if ($request->has('barang_id') && is_array($request->barang_id) && !empty($request->barang_id)) {
            $barangUnitKerja = Barang::whereIn('id', $request->barang_id)->pluck('unitkerja_id')->toArray();
            $unitKerjaIds = array_merge($unitKerjaIds, $barangUnitKerja);
        }
        
        // Proses ini akan menggabungkan, memfilter duplikat, dan menghapus nilai null
        $uniqueUnitKerjaIds = array_filter(array_unique($unitKerjaIds));

        $unitkerja = Unitkerja::whereIn('id',$uniqueUnitKerjaIds)->get();
        // dd($unitkerja);

       $approvers = $unitkerja->map(function ($approver) {
            return [
                'id' => $approver->id,
                'nama' => $approver->kode,
            ];
        });

        // dd($approvers);
        return response()->json(['approvers' => $approvers]);
    }


    public function edit($id)
    {
        $title = "Edit Form Peminjaman";
        $user = User::where('id', auth()->user()->id)->first();
        $peminjaman = Peminjaman::with('detail_peminjaman.barang')->where('id', decrypt($id))->firstOrFail();
        $dataRuangan = Ruangan::with('gedung')->where('bisa_pinjam', 1)->get();
        $barangPeminjaman = $peminjaman->detail_peminjaman->map(function ($detail) {
            return [
                'id' => $detail->barang_id,
                'nama' => $detail->barang->nama . ' (Stok: ' . $detail->barang->jumlah . ')',
                'jumlah' => $detail->jml_barang,
            ];
        });

        $approvers = $peminjaman->persetujuan_peminjaman->map(function ($approver) {
            return [
                'id' => $approver->unitkerja_id ?? null,
                'nama' => $approver->unit_kerja->kode,
            ];
        });
        // dd($peminjaman);

        return view('mahasiswa.peminjaman.edit', compact('title', 'peminjaman', 'user', 'dataRuangan', 'barangPeminjaman','approvers'));
    }


    public function update(Request $request, $id)
    {
        // Decrypt ID
        $id = decrypt($id);

        // 1. Validasi dasar
        $request->validate([
            'kegiatan'           => 'required|string|max:255',
            'waktu_peminjaman'   => 'required|date',
            'waktu_pengembalian' => 'required|date|after_or_equal:waktu_peminjaman',
            'no_telepon'         => 'required',
        ]);

        // 2. Cek minimal satu item (ruangan atau barang)
        if (!$request->filled('ruangan_id') && !$request->has('barang_id')) {
            return back()->with('msg', 'Anda harus memilih minimal satu ruangan atau satu barang untuk dipinjam.')->withInput();
        }

        try {
            DB::beginTransaction();

            // 3. Temukan peminjaman yang akan diupdate
            $peminjaman = Peminjaman::with('detail_peminjaman')->findOrFail($id);

            // 4. Simpan data lama untuk pengecekan perubahan
            $oldRuanganId = $peminjaman->ruangan_id;
            $oldStart = $peminjaman->waktu_peminjaman;
            $oldEnd = $peminjaman->waktu_pengembalian;
            $oldBarang = $peminjaman->detail_peminjaman->pluck('barang_id')->toArray();

            // 5. Update data utama peminjaman
            $peminjaman->update([
                'kegiatan'           => $request->kegiatan,
                'no_peminjam'        => $request->no_telepon,
                'waktu_peminjaman'   => date('Y-m-d H:i:s', strtotime($request->waktu_peminjaman)),
                'waktu_pengembalian' => date('Y-m-d H:i:s', strtotime($request->waktu_pengembalian)),
                'ruangan_id'         => $request->ruangan_id,
            ]);

            // 6. Handle perubahan detail barang
            $unitKerjaIds = [];
            $newBarangIds = $request->barang_id ?? [];

            // 6a. Hapus barang yang dihapus dari peminjaman
            foreach ($peminjaman->detail_peminjaman as $detail) {
                if (!in_array($detail->barang_id, $newBarangIds)) {
                    $detail->delete();
                }
            }

            // 6b. Tambahkan/update barang yang ada
            if ($request->has('barang_id') && is_array($request->barang_id)) {
                foreach ($request->barang_id as $index => $barangId) {
                    $jmlBarang = $request->jumlah_barang[$index] ?? 1;

                    // Cek apakah barang sudah ada
                    $existingDetail = Detailpeminjaman::where([
                        'peminjaman_id' => $peminjaman->id,
                        'barang_id' => $barangId
                    ])->first();

                    if ($existingDetail) {
                        // Update jumlah jika berbeda
                        if ($existingDetail->jml_barang != $jmlBarang) {
                            $existingDetail->update(['jml_barang' => $jmlBarang]);
                        }
                    } else {
                        // Tambahkan baru
                        Detailpeminjaman::create([
                            'peminjaman_id' => $peminjaman->id,
                            'barang_id'     => $barangId,
                            'jml_barang'    => $jmlBarang,
                        ]);
                    }

                    // Kumpulkan unit kerja
                    $barang = Barang::find($barangId);
                    if ($barang && $barang->ruangan->unitkerja_id) {
                        $unitKerjaIds[] = $barang->ruangan->unitkerja_id;
                    }
                }
            }

            // 7. Kumpulkan unit kerja dari ruangan (jika ada)
            if ($request->filled('ruangan_id')) {
                $ruangan = Ruangan::find($request->ruangan_id);
                if ($ruangan && $ruangan->unitkerja_id) {
                    $unitKerjaIds[] = $ruangan->unitkerja_id;
                }
            }

            // 8. Cek apakah perlu mereset persetujuan
            $significantChange = false;
            $changes = [
                'ruangan' => ($oldRuanganId != $request->ruangan_id),
                'waktu_mulai' => ($oldStart != $request->waktu_peminjaman),
                'waktu_selesai' => ($oldEnd != $request->waktu_pengembalian),
                'barang' => ($oldBarang != $newBarangIds)
            ];

            if (in_array(true, $changes)) {
                $significantChange = true;

                // Hapus semua persetujuan lama
                PersetujuanPeminjaman::where('peminjaman_id', $peminjaman->id)->delete();

                // Buat ulang persetujuan
                $kerumahtanggan = User::where('level', 'kerumahtanggaan')->firstOrFail();
                PersetujuanPeminjaman::create([
                    'peminjaman_id' => $peminjaman->id,
                    'approval_role' => 'kerumahtanggan', // pastikan string ini sesuai dengan kolom jabatan/role
                    'status'        => 'menunggu',
                    'unitkerja_id'  => $kerumahtanggan->unitkerja_id
                ]);

                // PersetujuanPeminjaman::create([
                //     'peminjaman_id' => $peminjaman->id,
                //     'approval_role' => 'kaprodi',
                //     'status'        => 'menunggu'
                // ]);

                $uniqueUnitKerjaIds = array_unique($unitKerjaIds);
                foreach ($uniqueUnitKerjaIds as $unitKerjaId) {
                    PersetujuanPeminjaman::create([
                        'peminjaman_id' => $peminjaman->id,
                        'approval_role' => 'baak',
                        'unitkerja_id'  => $unitKerjaId,
                        'status'        => 'menunggu'
                    ]);
                }
            }

            DB::commit();

            $message = 'Berhasil Memperbarui Pengajuan Peminjaman';
            if ($significantChange) {
                $message .= '. Perubahan signifikan memerlukan persetujuan ulang';
            }

            return redirect()->route('mahasiswa.peminjaman.index')->with('msg', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui peminjaman: ' . $e->getMessage());
            return back()->with('msg', 'Terjadi kesalahan. Gagal Memperbarui Pengajuan Peminjaman.')->withInput();
        }
    }


    public function destroy($id)
    {
        Peminjaman::where("id", $id)->delete();
        return back()->with(['msg' => 'Berhasil Menghapus Data', 'class' => 'alert-success']);
    }



    public function hapusdetail($id)
    {
        // dd($id);
        Detailpeminjaman::where("id", $id)->delete();
        return response()->json([
            'success' => true,
        ]);
    }



    public function cetakPdf($id)
    {
        try {
            $title = 'Cetak Peminjaman';
            // // $kaprodi = PersetujuanPeminjaman::with('user')->where('peminjaman_id', decrypt($id))->where('approval_role','kaprodi');
            // $kaprodi = PersetujuanPeminjaman::with('user')->where('peminjaman_id', decrypt($id))->firstOrFail();
            // dd($kaprodi);
            $dataPeminjaman = Peminjaman::with(['user', 'detail_peminjaman.barang', 'persetujuan_peminjaman.user'])
                ->where('id', decrypt($id))
                ->firstOrFail();

            return view('mahasiswa.peminjaman.cetakpdf', compact('title', 'dataPeminjaman'));
        } catch (ModelNotFoundException $e) {
            abort(404);
        }
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
}
