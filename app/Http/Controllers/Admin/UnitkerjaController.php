<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unitkerja;
use Illuminate\Http\Request;

class UnitkerjaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Data Unit Kerja";
        $unitKerja = Unitkerja::all();
        // dd($unitKerja);
        return view('admin.unit.data', compact(
            'title',
            'unitKerja'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = "Tambah Data Unit Kerja";
        return  view('admin.unit.create', compact('title'));
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
            'kode'     => 'required|unique:unitkerjas',
            'nama'     => 'required|max:255',
            
        ]);
        // dd($request->file('foto')->getExtension());
        // $validatedData['']

        
        Unitkerja::create($validatedData);
        // dd();
        return redirect()->route('admin.unit.index')->with(['msg' => 'Data Berhasil Disimpan', 'class' => 'alert-success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $title = "Hapus Data Unit";
        $dataUnit = Unitkerja::where("id", $id)->first();
        $view = view('admin.unit.delete', compact('title', 'dataUnit'))->render();
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
        $title = "Edit Data Unit Kerja";
        $dataUnit = Unitkerja::where("id", $id)->first();
        // dd($dataUnit);
        $view = view('admin.unit.update', compact('title', 'dataUnit'));
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
        $dataUnit = Unitkerja::where("id", $id)->first();
        $rules = [
            'nama'     => 'required|max:255'
        ];
        
        // $validatedData['']

        if($request->kode != $dataUnit->kode) {
            $rules['kode'] = 'required|unique:unitkerjas';
        }
        $validatedData = $request->validate($rules);
        
        Unitkerja::where('id', $id)->update($validatedData);
        // dd();
        return redirect()->route('admin.unit.index')->with(['msg' => 'Berhasil Mengubah Data', 'class' => 'alert-success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        Unitkerja::where('id', $id)->delete();
        return back()->with(['msg' => 'Berhasil Menghapus Data', 'class' => 'alert-success']);
    }
}
