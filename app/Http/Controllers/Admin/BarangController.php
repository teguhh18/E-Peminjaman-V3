<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Gedung;
use App\Models\Kategori;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\BarangExport;
use App\Models\Unitkerja;
use Excel;
// use Maatwebsite\Excel\Excel;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Data Barang";
        $dataBarang = Barang::with('ruangan.gedung')->whereHas('ruangan')->get();


        // dd($dataBarang[0]->ruangan->lantai);
        return view('admin.barang.data', compact(
            'title',
            'dataBarang',
        ));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = "Tambah Data Barang";
        $ruangans = Ruangan::with('gedung')->get();
        $kategoris = Kategori::all();
        $unitkerjas = Unitkerja::all();
        $lastBarang = Barang::orderBy('kode', 'desc')->first();
        if ($lastBarang) {
            $lastNumber = intval(substr($lastBarang->kode, 2)); // Mengambil angka dari kode, misal '0001'
            // dd($lastBarang->kode);
            $newNumber = $lastNumber + 1;
            $newKode = 'B-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT); // Membuat kode baru, misal 'B-0002'
        } else {
            $newKode = 'B-0001'; // Kode default jika belum ada data
        }
        return  view('admin.barang.create', compact(
            'title',
            'ruangans',
            'newKode',
            'kategoris',
            'unitkerjas',
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tgl_perolehan = date('Y-m-d', strtotime($request->tgl_perolehan));
        // dd($tgl_perolehan);

        $validatedData  = $request->validate([
            'nama'     => 'required|max:255',
            'kode'     => 'required|unique:gedungs',
            // "kategori_id" => 'required',
            "tgl_perolehan" => '',
            "ruangan_id" => 'required',
            "unitkerja_id" => '',
            "harga_perolehan" => '',
            "jumlah" => '',
            "kondisi" => '',
            "status" => '',
            "bisa_pinjam" => 'required',
            "deskripsi" => '',
            'foto' => 'image|file|max:2048',
        ]);

        $validatedData['tgl_perolehan'] = $tgl_perolehan;
        if ($request->file('foto')) {
            $validatedData['foto'] = $validatedData['kode'] . "-" . date('His') . "." . $request->file('foto')->getClientOriginalExtension();
            $request->file('foto')->storeAs('public/barangs', $validatedData['foto']);
        }
        Barang::create($validatedData);
        // dd();
        return redirect()->route('admin.barang.index')->with(['msg' => 'Data Berhasil Disimpan', 'class' => 'alert-success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $title = "Hapus Barang";
        $dataBarang = Barang::where("id", $id)->first();
        $view = view('admin.barang.delete', compact('title', 'dataBarang'))->render();
        return response()->json([
            'success' => true,
            'html' => $view
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $title = "Tambah Data Barang";
        $ruangans = Ruangan::with('gedung')->get();
        $dataBarang = Barang::where('id', $id)->first();
        $kategoris = Kategori::all();
        $unitkerjas = Unitkerja::all();

        return  view('admin.barang.update', compact(
            'title',
            'dataBarang',
            'ruangans',
            'kategoris',
            'unitkerjas'
        ));
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
        $dataBarang = Barang::where("id", $id)->first();
        $validatedData  = $request->validate([
            'nama'     => 'required|max:255',
            "kategori_id" => '',
            "tgl_perolehan" => '',
            "ruangan_id" => 'required',
            "unitkerja_id" => '',
            "harga_perolehan" => '',
            "jumlah" => '',
            "kondisi" => '',
            "status" => '',
            "deskripsi" => '',
            'foto' => 'image|file|max:2048',
        ]);
        $validatedData['tgl_perolehan'] = date('Y-m-d', strtotime($request->tgl_perolehan));
        if ($request->file('foto')) {
            // dd(Storage::exists($path));
            if ($dataBarang->foto) {
                Storage::delete('public/barangs/' . $dataBarang->foto);
            }
            $validatedData['foto'] = $validatedData['nama'] . "-" . date('His') . "new." . $request->file('foto')->getClientOriginalExtension();
            $request->file('foto')->storeAs('public/barangs', $validatedData['foto']);
        }

        Barang::where('id', $id)->update($validatedData);
        // dd();
        return redirect()->route('admin.barang.index')->with(['msg' => 'Berhasil Mengubah Data', 'class' => 'alert-success']);
    }

    public function filter(Request $request)
    {
        $title = "Filter Barang";
        $dataGedung = Gedung::all();

        // Jika ada 'ruangan_id' yang diterima dari permintaan
        if ($request->has('ruangan_id')) {
            // Ambil data barang berdasarkan ruangan yang dipilih
            $dataBarang = Barang::where('ruangan_id', $request->ruangan_id)
                ->get();

            // Ubah nilai kondisi dan status 
            foreach ($dataBarang as $barang) {
                // Ubah nilai kondisi
                switch ($barang->kondisi) {
                    case 1:
                        $barang->kondisi = 'Baik';
                        break;
                    case 2:
                        $barang->kondisi = 'Rusak Ringan';
                        break;
                    default:
                        $barang->kondisi = 'Rusak Berat';
                        break;
                }

                // Ubah nilai status
                switch ($barang->status) {
                    case 1:
                        $barang->status = 'Aktif';
                        break;
                    case 2:
                        $barang->status = 'Dihapuskan';
                        break;
                    default:
                        $barang->status = 'Diperbaiki';
                        break;
                }
            }

            // Kembalikan data barang dalam bentuk JSON
            return response()->json($dataBarang);
        }

        return view('admin.barang.filterBarang', compact(
            'title',
            'dataGedung'
        ));
    }

    public function ruanganByGedung($gedung_id)
    {
        $dataRuangan = Ruangan::where('gedung_id', $gedung_id)->get();
        return response()->json($dataRuangan);
    }

    public function QrCode($ruangan_id)
    {
        $title = "QR Code Barang";
        $dataBarang = Barang::where('ruangan_id', $ruangan_id)
            ->get();
        return view('admin.barang.QrCode', compact(
            'title',
            'dataBarang'
        ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Barang $barang)
    {
        if ($barang->foto) {
            Storage::delete('public/barangs/' . $barang->foto);
        }
        Barang::where("id", $barang->id)->delete();
        return back()->with(['msg' => 'Berhasil Menghapus Data', 'class' => 'alert-success']);
    }

    public function exportExcel()
    {
        return Excel::download(new BarangExport, 'barang-excel.xlsx');
    }
}
