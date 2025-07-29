<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Detailpeminjaman;
use App\Models\Peminjaman;
use App\Models\PersetujuanPeminjaman;
use App\Models\Ruangan;
use App\Models\Unitkerja;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Riwayat Peminjaman";
        $riwayatPeminjaman = Peminjaman::with(['user', 'ruangan.gedung', 'persetujuan_peminjaman'])
            ->whereIn('status_peminjaman', ['ditolak', 'selesai'])
            ->orderBy('id', 'desc')
            ->get();
        return view('admin.peminjaman.data', compact(
            'title',
            'riwayatPeminjaman'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = "Buat Peminjaman";
        $mahasiswa = User::where('level', 'mahasiswa')->get();
        $dataRuangan = Ruangan::with('gedung')->where('bisa_pinjam', 1)->get();
        return view('admin.peminjaman.create', compact('title', 'dataRuangan', 'mahasiswa'))->render();
    }

    public function getUser(Request $request)
    {
        // $user = User::find($request->user_id);
        $user = User::with('mahasiswa')->find($request->user_id);
        return response()->json($user);
    }

    public function modalApprover()
    {
        // $approvers = User::with('unitkerja')->where('level', '!=', 'mahasiswa')->get();
        $approvers = Unitkerja::all();
        // dd($approvers);
        $view =  view('admin.peminjaman.modal-approver', compact('approvers'))->render();
        return response()->json([
            'success' => true,
            'html' => $view
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 1. Validasi Awal
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
                'user_id'            => $request->user_id,
                'kegiatan'           => $request->kegiatan,
                'no_peminjam'        => $request->no_telepon,
                'waktu_peminjaman'   => date('Y-m-d H:i:s', strtotime($request->waktu_peminjaman)),
                'waktu_pengembalian' => date('Y-m-d H:i:s', strtotime($request->waktu_pengembalian)),
                'ruangan_id'         => $request->ruangan_id,
            ];

            // Buat data peminjaman utama
            $peminjamanBaru = Peminjaman::create($dataPeminjaman);

            // 5. Validasi dan Simpan Detail Barang (jika ada)
            $unitKerjaIds = []; // Array untuk menampung ID unit kerja dari barang yang dipinjam

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

            // Jika Pilih Approver Secara Manual, Buat Persetujuan Peminjaman Berdasarkan Approver yang Dipilih
            if ($request->approver_id) {
                foreach ($request->approver_id as $approvers) {
                    $approver = Unitkerja::find($approvers);
                    PersetujuanPeminjaman::create([
                        'peminjaman_id' => $peminjamanBaru->id,
                        // Anda mungkin perlu menyimpan target unit kerja di sini jika ada beberapa BAAK per unit
                        'unitkerja_id' => $approver->id,
                        'status'        => 'menunggu'
                    ]);
                }
            } else {
                //    Jika tidak pilih approver secara manual, jalankan ini
                // A. Persetujuan Kerumahtanggaan (Selalu ada)
                $kerumahtanggan = User::where('level', 'kerumahtanggaan')->firstOrFail();
                PersetujuanPeminjaman::create([
                    'peminjaman_id' => $peminjamanBaru->id,
                    'status'        => 'menunggu',
                    'unitkerja_id'        => $kerumahtanggan->unitkerja_id
                ]);

                // C. Persetujuan BAAK (Berdasarkan unit kerja aset yang dipinjam, unik)
                $uniqueUnitKerjaIds = array_unique($unitKerjaIds);
                foreach ($uniqueUnitKerjaIds as $unitKerjaId) {
                    PersetujuanPeminjaman::create([
                        'peminjaman_id' => $peminjamanBaru->id,
                        // Anda mungkin perlu menyimpan target unit kerja di sini jika ada beberapa BAAK per unit
                        'unitkerja_id' => $unitKerjaId,
                        'status'        => 'menunggu'
                    ]);
                }
            }

            // Jika semua proses di atas berhasil, commit transaksi
            DB::commit();
            return redirect()->route('admin.booking.index')->with(['msg' => 'Berhasil Membuat Peminjaman', 'class' => 'alert-success']);
        } catch (\Exception $e) {
            // Jika ada error di mana pun dalam blok 'try', batalkan semua
            DB::rollBack();
            // simpan error di log
            Log::error('Gagal menyimpan peminjaman: ' . $e->getMessage());
            return back()->with(['msg' => 'Terjadi Kesalahan, Gagal Membuat Peminjaman', 'class' => 'alert-danger'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Peminjaman  $peminjaman
     * @return \Illuminate\Http\Response
     */
    public function show(Peminjaman $peminjaman)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Peminjaman  $peminjaman
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $title = "Edit Form Peminjaman";
        $users = User::where('level', 'mahasiswa')->get();
        $peminjaman = Peminjaman::with('detail_peminjaman.barang')->where('id', decrypt($id))->firstOrFail();
        $dataRuangan = Ruangan::with('gedung')->where('bisa_pinjam', 1)->get();

        // Ambil data barang yang sudah dipinjam sebelumnya
        $barangPeminjaman = $peminjaman->detail_peminjaman->map(function ($detail) {
            return [
                'id' => $detail->barang_id,
                'nama' => $detail->barang->nama,
                'jumlah' => $detail->jml_barang,
            ];
        });
        // 1. Kumpulkan semua ID unit kerja dari aset yang dipinjam
        $assetUnitKerjaIds = [];
        if ($peminjaman->ruangan) {
            $assetUnitKerjaIds[] = $peminjaman->ruangan->unitkerja_id;
        }
        foreach ($peminjaman->detail_peminjaman as $detail) {
            if ($detail->barang) {
                $assetUnitKerjaIds[] = $detail->barang->unitkerja_id;
            }
        }
        $uniqueAssetUnitKerjaIds = array_unique($assetUnitKerjaIds);
        // dd($uniqueAssetUnitKerjaIds);

        // 2. Filter persetujuan untuk mendapatkan approver "tambahan" (manual)
        $approvers = $peminjaman->persetujuan_peminjaman
            ->filter(function ($persetujuan) use ($uniqueAssetUnitKerjaIds) {
                // Ambil persetujuan HANYA JIKA unitkerja_id-nya TIDAK ADA di dalam daftar unit kerja aset
                return !in_array($persetujuan->unitkerja_id, $uniqueAssetUnitKerjaIds);
            })
            ->map(function ($approver) {
                // Format data yang sudah difilter
                return [
                    'id'   => $approver->unitkerja_id,
                    'nama' => $approver->unit_kerja->kode,
                ];
            })->values();
        // dd($approvers);
        return view('admin.peminjaman.edit', compact('title', 'peminjaman', 'users', 'dataRuangan', 'barangPeminjaman', 'approvers'));
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
        $id = decrypt($id);

        // 1. Validasi dasar
        $request->validate([
            'kegiatan'           => 'required|string|max:255',
            'waktu_peminjaman'   => 'required|date',
            'waktu_pengembalian' => 'required|date|after_or_equal:waktu_peminjaman',
            'no_telepon'         => 'required',
            'approver_id'         => 'required',
        ]);

        try {
            DB::beginTransaction();

            $peminjaman = Peminjaman::with('persetujuan_peminjaman')->findOrFail($id);

            // 2. Update data utama peminjaman
            $peminjaman->update([
                'kegiatan'           => $request->kegiatan,
                'waktu_peminjaman'   => date('Y-m-d H:i:s', strtotime($request->waktu_peminjaman)),
                'waktu_pengembalian' => date('Y-m-d H:i:s', strtotime($request->waktu_pengembalian)),
                'ruangan_id'         => $request->ruangan_id,
            ]);

            // 3. Sinkronisasi detail barang (gunakan sync() agar lebih bersih)
            $barangIds = $request->input('barang_id', []);
            $jumlahBarang = $request->input('jumlah_barang', []);
            $syncDataBarang = [];
            foreach ($barangIds as $key => $barangId) {
                $syncDataBarang[$barangId] = ['jml_barang' => $jumlahBarang[$key]];
            }
            $peminjaman->barangs()->sync($syncDataBarang); // "barangs() itu fungsi di model Peminjaman"

            // ## LOGIKA UNTUK SINKRONISASI PERSETUJUAN BERDASARKAN UNIT KERJA ##
            // 4. Kumpulkan semua ID unit kerja yang dibutuhkan untuk peminjaman INI
            $newUnitKerjaIds = $request->input('approver_id', []);

            // 5. Ambil daftar unit kerja yang LAMA dari database
            $oldUnitKerjaIds = $peminjaman->persetujuan_peminjaman->pluck('unitkerja_id')->toArray();

            // 6. Bandingkan untuk menemukan apa yang harus ditambah dan dihapus
            $idsToAdd = array_diff($newUnitKerjaIds, $oldUnitKerjaIds);
            $idsToRemove = array_diff($oldUnitKerjaIds, $newUnitKerjaIds);

            // 7. Hapus persetujuan yang unit kerjanya sudah tidak relevan
            if (!empty($idsToRemove)) {
                PersetujuanPeminjaman::where('peminjaman_id', $peminjaman->id)
                    ->whereIn('unitkerja_id', $idsToRemove)
                    ->delete();
            }

            // 8. Tambahkan persetujuan baru untuk unit kerja yang baru ditambahkan
            foreach ($idsToAdd as $unitKerjaId) {
                PersetujuanPeminjaman::create([
                    'peminjaman_id' => $peminjaman->id,
                    'unitkerja_id'  => $unitKerjaId,
                    'status'        => 'menunggu'
                ]);
            }

            // ## LOGIKA UNTUK EVALUASI ULANG STATUS PEMINJAMAN ##

            // 1. Muat ulang relasi persetujuan untuk mendapatkan data terbaru setelah diubah
            $peminjaman->load('persetujuan_peminjaman');
            $semuaPersetujuan = $peminjaman->persetujuan_peminjaman;

            // 2. Cek kondisi
            $adaYangMenunggu = $semuaPersetujuan->contains('status', 'menunggu');
            $semuaMenyetujui = !$adaYangMenunggu && !$semuaPersetujuan->isEmpty(); // Benar jika tidak ada yang menunggu & daftar tidak kosong

            // 3. Tetapkan status akhir yang benar
            if ($adaYangMenunggu) {
                // Jika ada satu saja yang masih menunggu
                // kembalikan status utama ke 'menunggu_persetujuan'.
                $peminjaman->update(['status_peminjaman' => 'menunggu']);
            } elseif ($semuaMenyetujui) {
                // Jika tidak ada yang menunggu (artinya semua sudah disetujui),
                // tetapkan status utama menjadi 'disetujui'.
                $peminjaman->update(['status_peminjaman' => 'disetujui']);
            }

            DB::commit();

            return redirect()->route('admin.booking.index')->with(['msg' => 'Berhasil Mengubah Peminjaman', 'class' => 'alert-success']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui peminjaman: ' . $e->getMessage());
            return back()->with(['msg' => 'Terjadi Kesalahan, Gagal Mengubah Peminjaman', 'class' => 'alert-danger'])->withInput();
        }
    }

    public function laporan()
    {
        $title = "Laporan";

        return view('admin.peminjaman.laporan', compact(
            'title',
        ));
    }

    public function fetchDataLaporan(Request $request)
    {
        // Validasi input
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Query untuk mengambil data sesuai rentang waktu
        $query = Peminjaman::with('ruangan', 'user', 'detail_peminjaman.barang')
            ->whereBetween('waktu_peminjaman', [$startDate, $endDate]);


        // Jika status dipilih, tambahkan kondisi where untuk status
        if ($request->filled('status')) {
            $status = $request->status;
            $query->where('status_peminjaman', $status);
        }

        // Ambil data sesuai query
        $dataPeminjaman = $query->get();

        return response()->json($dataPeminjaman);
    }

    public function cetakLaporan(Request $request)
    {
        $title = "Laporan Peminjaman";
        $jsonResponse = $this->fetchDataLaporan($request);

        //  Ambil data mentah (raw data) dari JsonResponse sebagai array
        $dataArray = $jsonResponse->getData(true);

        // Ubah array menjadi Laravel Collection
        $dataPeminjaman = collect($dataArray);
        // dd($dataPeminjaman);
        return view('admin.peminjaman.cetakLaporan', compact(
            'title',
            'dataPeminjaman'
        ));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Peminjaman  $peminjaman
     * @return \Illuminate\Http\Response
     */
    public function destroy(Peminjaman $peminjaman)
    {
        //
    }
}
