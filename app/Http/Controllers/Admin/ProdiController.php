<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use App\Models\Unitkerja;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title  = "Data Prodi";
        $prodi = Prodi::with(['unitkerja'])->get();
        // dd($prodi);
        return  view('admin.prodi.data', compact('title', 'prodi'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = "Tambah Data Prodi";
        $unit = Unitkerja::all();
        return  view('admin.prodi.create', compact('title', 'unit'));
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
            'kode_prodi'     => 'required|unique:prodi',
            'nama'     => 'required|max:255',
            'unitkerja_id'     => 'required',
            
        ]);
        Prodi::create($validatedData);
        // dd();
        return redirect()->route('admin.prodi.index')->with(['msg' => 'Data Berhasil Disimpan', 'class' => 'alert-success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Prodi  $prodi
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $title = "Hapus Data Prodi";
        $dataProdi = Prodi::where("id", $id)->first();
        $view = view('admin.prodi.delete', compact('title', 'dataProdi'))->render();
        return response()->json([
            'success' => true,
            'html' => $view
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Prodi  $prodi
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $prodi = Prodi::find($id);
        $unit = Unitkerja::all();
        $title = "Edit Data Prodi";
        return  view('admin.prodi.update', compact('title', 'prodi','unit'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Prodi  $prodi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $dataProdi = Prodi::where("id", $id)->first();
        $rules = [
            'nama'     => 'required|max:255',
            'unitkerja_id'     => 'required',
        ];
        
        // $validatedData['']

        if($request->kode_prodi != $dataProdi->kode_prodi) {
            $rules['kode_prodi'] = 'required|unique:prodi';
        }
        $validatedData = $request->validate($rules);
        
        Prodi::where('id', $id)->update($validatedData);
        // dd();
        return redirect()->route('admin.prodi.index')->with(['msg' => 'Berhasil Mengubah Data', 'class' => 'alert-success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Prodi  $prodi
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         Prodi::where('id', $id)->delete();
        return back()->with(['msg' => 'Berhasil Menghapus Data', 'class' => 'alert-success']);
    }
}
