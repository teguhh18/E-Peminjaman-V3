<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unitkerja;
use App\Models\User;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class MahasiswaController extends Controller
{

    public function index(Request $request)
    {
        $title = "Data Mahasiswa";
        if (!empty(Auth::user()->fakultas_kode)) {
            $uriProdi = env('API') . '/api/prodi/getFakultasId?fakultas_id=' . Auth::user()->fakultas_kode;
        } else {
            $uriProdi = env('API') . '/api/prodi';
        }
        $dataProdi = json_decode(file_get_contents($uriProdi));

        if (isset($request->prodi)) {
            $angkatan = $request->angkatan;
            $idProdi = $request->prodi;
            $dataMahasiswa = Mahasiswa::where("kode_program_studi", $request->prodi)
                ->whereRaw("LEFT(npm_mahasiswa,2) = '$angkatan'")
                ->get();
        } else {
            $dataMahasiswa = Mahasiswa::limit(0)->get();
            $idProdi = false;
            $angkatan = false;
        }
        return view('admin.master.mahasiswa.index', compact(
            'title',
            'dataMahasiswa',
            'dataProdi',
            'angkatan',
            'idProdi'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        ini_set('max_execution_time', '5000');
        $url = env("API") . "/api/mahasiswa?angkatan=" . $request->angkatan;
        $dataMahasiswa = json_decode(file_get_contents($url));
        foreach ($dataMahasiswa->data as $key) {
            $cekData = User::where("username", $key->npm);
            if ($cekData->count() > 0) {
                Mahasiswa::where("user_id", $cekData->first()->id)->update([
                    'kode_program_studi' => $key->id_prodi,
                    'nama_program_studi' => $key->nama_prodi,
                    'kode_fakultas' => $key->id_fakultas,
                    'nama_fakultas' => $key->nama_fakultas,
                    'nama_program_studi_english' => $key->nama_prodi_eng,
                    'nama_fakultas_english' => $key->nama_fakultas_eng,
                ]);
            } else {
                $akun = User::create([
                    'username' => $key->npm,
                    'email' => $key->npm . "@teknokrat.ac.id",
                    'name' => $key->nama,
                    'level' => 'mahasiswa',
                    'password' => Hash::make($key->npm),
                ]);

                Mahasiswa::create([
                    'npm_mahasiswa' => $key->npm,
                    'nama_mahasiswa' => $key->nama,
                    'kode_program_studi' => $key->id_prodi,
                    'nama_program_studi' => $key->nama_prodi,
                    'kode_fakultas' => $key->id_fakultas,
                    'nama_fakultas' => $key->nama_fakultas,
                    'nama_program_studi_english' => $key->nama_prodi_eng,
                    'nama_fakultas_english' => $key->nama_fakultas_eng,
                    'user_id' => $akun->id,
                ]);
            }
        }
        return back()->with(['msg' => 'Sinkronisasi Berhasil', 'class' => 'alert-success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $title = "Hapus User";
        $mahasiswa = User::where("id", $id)->first();
        $view = view('admin.mahasiswa.delete', compact('title', 'mahasiswa'))->render();
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
    public function edit($id) {}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findorfail($id);
        $user->update([
            'password' => Hash::make($user->username),
        ]);
        return back()->with(['msg' => 'Reset Password Berhasil', 'class' => 'alert-success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {}
}
