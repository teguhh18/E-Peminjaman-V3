<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gedung;
use App\Models\Ruangan;
use App\Models\Unitkerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RuanganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Data Ruangan";
        $dataRuangan = Ruangan::with('gedung', 'unitkerja')->whereHas('gedung')->whereHas('unitkerja')->get();
        // $dataRuangan = Ruangan::whereHas('gedung', function ($query) {
        //     $query->whereNotNull('id');
        // })->whereHas('unitkerja', function ($query) {
        //     $query->whereNotNull('id');
        // })->get();
        // dd($dataRuangan[1]->gedung->kode);
        return view('admin.ruangan.data', compact(
            'title',
            'dataRuangan'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = "Tambah Data Ruangan";
        $gedungs = Gedung::all();
        $unitkerjas = Unitkerja::all();

        return  view('admin.ruangan.create', compact(
            'title',
            'gedungs',
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
        // dd($request);
        $validatedData  = $request->validate([
            'nama_ruangan'     => 'required|max:255',
            'kode_ruangan'     => 'required|unique:ruangans',
            'gedung_id'     => 'required',
            'unitkerja_id'     => 'required',
            'lantai'     => 'required',
            'kapasitas'     => 'required',
            'luas'     => '',
            'tipe'     => '',
            'kondisi'     => 'required',
            'bisa_pinjam'     => 'required',
            'foto_ruangan' => 'image|file|max:2048',
            'status'     => 'required',
        ]);

        if ($request->file('foto_ruangan')) {
            $validatedData['foto_ruangan'] = $validatedData['nama_ruangan'] . "-" . date('His') . "." . $request->file('foto_ruangan')->getClientOriginalExtension();
            $request->file('foto_ruangan')->storeAs('public/ruangans', $validatedData['foto_ruangan']);
        }
        Ruangan::create($validatedData);

        return redirect()->route('admin.ruangan.index')->with(['msg' => 'Data Berhasil Disimpan', 'class' => 'alert-success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $title = "Hapus Ruangan";
        $dataRuangan = Ruangan::where("id", $id)->first();
        $view = view('admin.ruangan.delete', compact('title', 'dataRuangan'))->render();
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
        $title = "Edit Data Ruangan";
        $gedungs = Gedung::all();
        $unitkerjas = Unitkerja::all();
        $ruangan = Ruangan::where('id', $id)->first();
        return  view('admin.ruangan.update', compact(
            'title',
            'ruangan',
            'gedungs',
            'unitkerjas',
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
        // dd($request);
        $dataRuangan = Ruangan::where("id", $id)->first();
        $validatedData  = $request->validate([
            'nama_ruangan'     => 'required|max:255',
            'gedung_id'     => 'required',
            'unitkerja_id'     => 'required',
            'lantai'     => 'required',
            'kapasitas'     => 'required',
            'luas'     => '',
            'tipe'     => '',
            'kondisi'     => 'required',
            'bisa_pinjam'     => '',
            'foto_ruangan' => 'image|file|max:2048',
            'status'     => '',
        ]);

        if ($request->file('foto_ruangan')) {
            // dd(Storage::exists($path));
            if ($dataRuangan->foto_ruangan) {
                Storage::delete('public/ruangans/' . $dataRuangan->foto_ruangan);
            }
            $validatedData['foto_ruangan'] = $validatedData['nama_ruangan'] . "-" . date('His') . "new." . $request->file('foto_ruangan')->getClientOriginalExtension();
            $request->file('foto_ruangan')->storeAs('public/ruangans', $validatedData['foto_ruangan']);
        }

        Ruangan::where('id', $id)->update($validatedData);
        return redirect()->route('admin.ruangan.index')->with(['msg' => 'Data Berhasil Diubah', 'class' => 'alert-success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ruangan $ruangan)
    {
        if ($ruangan->foto_ruangan) {
            Storage::delete('public/ruangans/' . $ruangan->foto_ruangan);
        }
        Ruangan::where("id", $ruangan->id)->delete();
        return back()->with(['msg' => 'Berhasil Menghapus Data', 'class' => 'alert-success']);
    }
}
