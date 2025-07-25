<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kegiatan;

use Illuminate\Support\Facades\Auth as Auth;

class KegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(Request $request)
    {
        // dd(Auth::user());
        // echo 'astaghfirullah';
        // die;
        // dd(auth()->user());
        // dd(Auth::user()->level == 'admin');
        // if (Auth::user()->level !== 'admin') {
        //     abort(403);
        // }
    }

    public function index()
    {
        $title = "Data Kegiatan";
        $dataKegiatan = Kegiatan::all();
        return view('admin.kegiatan.data', compact(
            'title',
            'dataKegiatan'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $judul = "Tambah Data Kegiatan";
        $view = view('admin.kegiatan.create', compact('judul'))->render();
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
        Kegiatan::create([
            'nama_kegiatan' => $request->nama_kegiatan,
            'deskripsi_kegiatan' => $request->deskripsi,
        ]);
        return back()->with(['msg' => 'Berhasil Menambah Data', 'class' => 'alert-success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $judul = "Hapus Kegiatan";
        $dataKegiatan = Kegiatan::where("id", $id)->first();
        $view = view('admin.kegiatan.delete', compact('judul', 'dataKegiatan'))->render();
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
        $judul = "Edit Data Kegiatan";
        $dataKegiatan = Kegiatan::where("id", $id)->first();
        $view = view('admin.kegiatan.update', compact('judul', 'dataKegiatan'))->render();
        return response()->json([
            'success' => true,
            'html' => $view
        ]);
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
        Kegiatan::where("id", $id)->update([
            'nama_kegiatan' => $request->nama_kegiatan,
            'deskripsi_kegiatan' => $request->deskripsi,
        ]);
        return back()->with(['msg' => 'Berhasil Merubah Data', 'class' => 'alert-success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Kegiatan::where("id", $id)->delete();
        return back()->with(['msg' => 'Berhasil Menghapus Data', 'class' => 'alert-success']);
    }
}
