<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\PeminjamanRuangan;
use App\Models\PersetujuanPeminjaman;
use App\Models\Ruangan;
use App\Models\User;
use DateTime;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class BookingController extends Controller
{

    public function index()
    {
        $title = "List Peminjaman";

        $user_id = auth()->id();
        $listPeminjaman = Peminjaman::with(['ruangan.gedung'])
            ->whereNotNull('ruangan_id')
            ->where('user_id', $user_id)
            ->orderBy('created_at', 'desc')
            ->get();

        // dd($listPeminjaman);

        return view('mahasiswa.ruangan.list_peminjaman', compact(
            'title',
            'listPeminjaman',
        ));
    }

    public function show($id)
    {
        $title = "Booking Ruangan";

        $ruangan = Ruangan::with('gedung')->findOrFail(decrypt($id));

        // Full Calendar untuk menampilkan jadwal peminjaman ruangan saat ini
        // $appointments = Peminjaman::where('ruangan_id', $ruangan->id)
        //     ->where('konfirmasi', 2) // konfirmasi 2 berarti sudah disetujui
        //     ->orderBy('waktu_peminjaman', 'asc')
        //     ->get();
        //     // dd($appointments);
        // $events = [];
        // foreach ($appointments as $appointment) {

        //     $start = new DateTime($appointment->waktu_pinjam);
        //     $end = new DateTime($appointment->waktu_selesai);

        //     // Format waktu ke format ISO8601
        //     $startIso = $start->format('Y-m-d\TH:i:s');
        //     $endIso = $end->format('Y-m-d\TH:i:s');

        //     $events[] = [
        //         'title' => $appointment->kegiatan . ' (' . $appointment->user->name . ') - ' . $appointment->ruangan->nama_ruangan,
        //         'start' => $startIso,
        //         'end' => $endIso,
        //     ];
        // }
        return view('mahasiswa.ruangan.create', compact(
            'title',
            'ruangan',
            // 'events'
        ));
    }

    public function edit($id)
    {
        $title = "Ubah Data Booking";
        // untuk mendapatkan satu peminjaman ruangan dengan ID yang diberikan
        $data = Peminjaman::with(['ruangan.gedung'])->findOrFail(decrypt($id));
        // ruangan terkait dengan peminjaman ruangan
        $ruangan = Ruangan::with('gedung')->findOrFail($data->ruangan_id);

        return  view('mahasiswa.ruangan.edit', compact(
            'title',
            'data',
            'ruangan'
        ));
    }


    public function store(Request $request)
    {

        // $baak = User::where('level', 'baak')->where('unitkerja_id', auth()->user()->unitkerja_id)->where('prodi_id', null)->get();
        // dd($baak);
        // Validasi input
        $dataValid = $request->validate([
            'ruangan_id' => 'required',
            'kegiatan' => 'required',
            'no_peminjam' => 'required',
            'waktu_peminjaman' => 'required|date',
            'waktu_pengembalian' => 'required|date|after_or_equal:waktu_peminjaman',
        ]);

        // Periksa apakah tanggal peminjaman dan tanggal selesai adalah di masa lalu
        if (strtotime($dataValid['waktu_peminjaman']) < strtotime('today') || strtotime($dataValid['waktu_pengembalian']) < strtotime('today')) {
            return back()->with('error', 'Tanggal peminjaman atau tanggal selesai tidak boleh di masa lalu.');
        }

        $dataValid['user_id'] = auth()->user()->id;
        $dataValid['waktu_peminjaman'] = date('Y-m-d H:i', strtotime($dataValid['waktu_peminjaman']));
        $dataValid['waktu_pengembalian'] = date('Y-m-d H:i', strtotime($dataValid['waktu_pengembalian']));

        // Periksa apakah ada peminjaman dengan waktu yang bertabrakan dan status 2
        $existingBooking = Peminjaman::where('ruangan_id', $dataValid['ruangan_id'])
            ->where('konfirmasi', 2) // Hanya cek jadwal yang sudah dikonfirmasi
            ->where(function ($query) use ($dataValid) {
                $query->where('waktu_peminjaman', '<', $dataValid['waktu_pengembalian'])
                    ->where('waktu_pengembalian', '>', $dataValid['waktu_peminjaman']);
            })
            ->exists();

        if ($existingBooking) {
            return back()->with('error', 'Ruangan sudah dibooking pada tanggal dan jam yang sama. Silakan coba pada tanggal atau jam lain.');
        }

        // Buat peminjaman baru
        $peminjaman = Peminjaman::create($dataValid);


        // 3. Persetujuan kerumahtanggaan (1 record, approver_id null)
        PersetujuanPeminjaman::create([
            'peminjaman_id'  => $peminjaman->id,
            'approval_role'  => 'kerumahtanggan',
            'unitkerja_id'   => null,
            'approver_id'    => null,
            'status'         => 'menunggu'
        ]);

        // 4. Persetujuan kaprodi ()
        $kaprodi = User::where('level', 'kaprodi')
            ->where('prodi_id', auth()->user()->prodi_id)
            ->first();

        if ($kaprodi) {
            PersetujuanPeminjaman::create([
                'peminjaman_id' => $peminjaman->id,
                'approval_role' => 'kaprodi',
                'unitkerja_id'  => null,
                'approver_id'   => null,
                'status'        => 'menunggu'
            ]);
        }

        // 5. Persetujuan baak per unitkerja (1 record per unit, approver_id null)
        $unitkerjaIds = collect();
        if ($request->ruangan_id) {
            $unitkerjaIds->push(Ruangan::find($request->ruangan_id)->unitkerja_id);
        }
        if ($request->has('barang_ids')) {
            $barangUnitkerjas = Barang::whereIn('id', array_keys($request->barang_ids))
                ->with('ruangan')->get()
                ->pluck('ruangan.unitkerja_id');
            $unitkerjaIds = $unitkerjaIds->merge($barangUnitkerjas);
        }
        $unitkerjaIds = $unitkerjaIds->unique()->filter();

        foreach ($unitkerjaIds as $ukid) {
            PersetujuanPeminjaman::create([
                'peminjaman_id' => $peminjaman->id,
                'approval_role' => 'baak',
                'unitkerja_id'  => $ukid,
                'approver_id'   => null,
                'status'        => 'menunggu'
            ]);
        }



        return redirect()->route('mahasiswa.ruangan.index')->with('success', 'Ruangan Berhasil Di Booking');
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $dataValid = $request->validate([
            'ruangan_id' => 'required',
            'kegiatan' => 'required',
            'no_peminjam' => 'required',
            'waktu_peminjaman' => 'required',
            'waktu_pengembalian' => 'required',
        ]);

        $dataValid['user_id'] = auth()->user()->id;
        $dataValid['waktu_peminjaman'] = date('Y-m-d H:i', strtotime($request->waktu_peminjaman));
        $dataValid['waktu_pengembalian'] = date('Y-m-d H:i', strtotime($request->waktu_pengembalian));

        // Periksa apakah ada peminjaman dengan tanggal dan jam yang sama dan status 2, kecuali untuk booking yang sedang diedit
        $existingBooking = Peminjaman::where('ruangan_id', $dataValid['ruangan_id'])
            ->where('konfirmasi', 2)
            ->where('id', '!=', decrypt($id))
            ->where(function ($query) use ($dataValid) {
                $query->whereBetween('waktu_peminjaman', [$dataValid['waktu_peminjaman'], $dataValid['waktu_pengembalian']])
                    ->orWhereBetween('waktu_pengembalian', [$dataValid['waktu_peminjaman'], $dataValid['waktu_pengembalian']])
                    ->orWhere(function ($query) use ($dataValid) {
                        $query->where('waktu_peminjaman', '<=', $dataValid['waktu_peminjaman'])
                            ->where('waktu_pengembalian', '>=', $dataValid['waktu_pengembalian']);
                    });
            })
            ->exists();

        if ($existingBooking) {
            return back()->with('error', 'Ruangan sudah dibooking pada tanggal dan jam yang sama. Silakan coba pada tanggal atau jam lain.');
        }

        // Update data peminjaman ruangan
        Peminjaman::where('id', decrypt($id))->update($dataValid);

        return redirect()->route('mahasiswa.ruangan.index')->with('success', 'Berhasil Edit Booking Ruangan');
    }




    public function PrintPdf($id)
    {
        $title = 'Cetak Peminjaman Ruangan';
        $decryptedId = decrypt($id);
        $dataBooking = Peminjaman::with(['user', 'ruangan.gedung'])
            ->where('id', $decryptedId)
            ->firstOrFail();

        return view('mahasiswa.ruangan.cetakpdf', compact('title', 'dataBooking'));
    }



    public function destroy($id)
    {
        Peminjaman::where("id", $id)->delete();
        return back()->with('success', 'Berhasil Menghapus Data');
    }
}
// $peminjaman = Ruangan::with('ruangan')->where('user_id', $user);
