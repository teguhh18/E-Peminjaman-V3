<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gedung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GedungController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Data Gedung";
        $dataGedung = Gedung::all();
        // dd($dataGedung);
        return view('admin.gedung.data', compact(
            'title',
            'dataGedung'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = "Tambah Data Gedung";
        return  view('admin.gedung.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData  = $request->validate([
            'nama'     => 'required|max:255',
            'kode'     => 'required|unique:gedungs',
            // 'sumber_dana'     => '',
            // 'besar_dana'     => '',
            'tahun'     => '',
            'lokasi'     => '',
            'foto' => 'image|file|max:2048',
            'jumlah_lantai'     => 'required|numeric',
        ]);
        // dd($request->file('foto')->getExtension());
        // $validatedData['']

        if ($request->file('foto')) {

            $validatedData['foto'] = $validatedData['kode'] . "-" . date('His') . "." . $request->file('foto')->getClientOriginalExtension();
            $request->file('foto')->storeAs('public/gedungs', $validatedData['foto']);
        }
        Gedung::create($validatedData);
        // dd();
        return redirect()->route('admin.gedung.index')->with(['msg' => 'Data Berhasil Disimpan', 'class' => 'alert-success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $title = "Hapus Gedung";
        $dataGedung = Gedung::where("id", $id)->first();
        $view = view('admin.gedung.delete', compact('title', 'dataGedung'))->render();
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
        $title = "Edit Data Gedung";
        $dataGedung = Gedung::where("id", $id)->first();
        // dd($dataGedung->id);
        $view = view('admin.gedung.update', compact('title', 'dataGedung'));
        return $view;
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
        $dataGedung = Gedung::where("id", $id)->first();
        $validatedData  = $request->validate([
            'nama'     => 'required|max:255',
            'sumber_dana'     => '',
            'besar_dana'     => '',
            'tahun'     => '',
            'lokasi'     => '',
            'foto' => 'image|file|max:2048',
            'jumlah_lantai'     => 'required|numeric',
        ]);
        // dd($request->file('foto')->getExtension());
        // $validatedData['']

        if ($request->file('foto')) {
            // dd(Storage::exists($path));
            if ($dataGedung->foto) {
                Storage::delete('public/gedungs/' . $dataGedung->foto);
            }
            $validatedData['foto'] = $validatedData['nama'] . "-" . date('His') . "new." . $request->file('foto')->getClientOriginalExtension();
            $request->file('foto')->storeAs('public/gedungs', $validatedData['foto']);
        }

        Gedung::where('id', $id)->update($validatedData);
        // dd();
        return redirect()->route('admin.gedung.index')->with(['msg' => 'Berhasil Mengubah Data', 'class' => 'alert-success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gedung $gedung)
    {
        if ($gedung->foto) {
            Storage::delete('public/gedungs/' . $gedung->foto);
        }
        Gedung::where("id", $gedung->id)->delete();
        return back()->with(['msg' => 'Berhasil Menghapus Data', 'class' => 'alert-success']);
    }
}
