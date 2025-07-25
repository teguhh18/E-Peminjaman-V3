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
        $user = User::with('prodi')->find($request->user_id);
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
        // dd($request->approver_id);
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
                $kerumahtanggan = User::where('level', 'kerumahtanggan')->firstOrFail();
                PersetujuanPeminjaman::create([
                    'peminjaman_id' => $peminjamanBaru->id,
                    'status'        => 'menunggu',
                    'unitkerja_id'        => $kerumahtanggan->unitkerja_id
                ]);

                $user = User::find($request->user_id);
                // B. Persetujuan Kaprodi (Berdasarkan prodi peminjam)
                PersetujuanPeminjaman::create([
                    'peminjaman_id' => $peminjamanBaru->id,
                    'status'        => 'menunggu',
                    'unitkerja_id' => $user->prodi->unitkerja_id,

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

            return redirect()->route('admin.booking.index')->with('msg', 'Berhasil Membuat Pengajuan Peminjaman');
        } catch (\Exception $e) {
            // Jika ada error di mana pun dalam blok 'try', batalkan semua
            DB::rollBack();

            Log::error('Gagal menyimpan peminjaman: ' . $e->getMessage());

            return back()->with('msg', 'Terjadi kesalahan. Gagal Membuat Pengajuan Peminjaman.')->withInput();
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
        // dd(decrypt($id));
        $title = "Edit Form Peminjaman";
        $users = User::where('level', 'mahasiswa')->get();

        $peminjaman = Peminjaman::with('detail_peminjaman.barang')->where('id', decrypt($id))->firstOrFail();
        // $approver = PersetujuanPeminjaman::with('user.unitkerja')->where('id', decrypt($id))->get();
        $dataRuangan = Ruangan::with('gedung')->where('bisa_pinjam', 1)->get();
        $barangPeminjaman = $peminjaman->detail_peminjaman->map(function ($detail) {
            return [
                'id' => $detail->barang_id,
                'nama' => $detail->barang->nama,
                'jumlah' => $detail->jml_barang,
            ];
        });
        $approvers = $peminjaman->persetujuan_peminjaman->map(function ($approver) {
            return [
                'id' => $approver->unitkerja_id ?? null,
                'nama' => $approver->unit_kerja->kode,
            ];
        });
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
        // dd($request->approver_id);
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
                if ($request->approver_id != null)  {
                    foreach ($request->approver_id as $approvers) {
                    $approver = Unitkerja::find($approvers);
                    PersetujuanPeminjaman::create([
                        'peminjaman_id' => $peminjaman->id,
                        // Anda mungkin perlu menyimpan target unit kerja di sini jika ada beberapa BAAK per unit
                        'unitkerja_id' => $approver->id,
                        'status'        => 'menunggu'
                    ]);
                }
                } else {
                    //    Jika tidak pilih approver secara manual, jalankan ini
                    // A. Persetujuan Kerumahtanggaan (Selalu ada)
                    $kerumahtanggan = User::where('level', 'kerumahtanggan')->firstOrFail();
                    PersetujuanPeminjaman::create([
                        'peminjaman_id' => $peminjaman->id,
                        'status'        => 'menunggu',
                        'unitkerja_id'        => $kerumahtanggan->unitkerja_id
                    ]);

                    // B. Persetujuan BAAK (Berdasarkan unit kerja aset yang dipinjam, unik)
                    $uniqueUnitKerjaIds = array_unique($unitKerjaIds);
                    foreach ($uniqueUnitKerjaIds as $unitKerjaId) {
                        PersetujuanPeminjaman::create([
                            'peminjaman_id' => $peminjaman->id,
                            // Anda mungkin perlu menyimpan target unit kerja di sini jika ada beberapa BAAK per unit
                            'unitkerja_id' => $unitKerjaId,
                            'status'        => 'menunggu'
                        ]);
                    }
                }
            }

            DB::commit();

            $message = 'Berhasil Memperbarui Pengajuan Peminjaman';
            if ($significantChange) {
                $message .= '. Perubahan signifikan memerlukan persetujuan ulang';
            }

            return redirect()->route('admin.booking.index')->with('msg', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui peminjaman: ' . $e->getMessage());
            return back()->with('msg', 'Terjadi kesalahan. Gagal Memperbarui Pengajuan Peminjaman.')->withInput();
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
            $query->where('konfirmasi', $status);
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
