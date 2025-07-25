<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unitkerja;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class MahasiswaController extends Controller
{

    public function list_mahasiwa($angkatan)
    {
        $title = "Data Mahasiswa";
        $url = env("URL_SISFO") . "/api/mahasiswa.php?angkatan=" . $angkatan;
        return $dataMahasiswa = json_decode(file_get_contents($url));
    }
    
    public function list_prodi()
    {
        $title = "Data Mahasiswa";
        $uriProdi = env('URL_SISFO') . '/api/programstudi.php?id_prodi=ALL';

        return $dataProdi = json_decode(file_get_contents($uriProdi));
         
    }

    public function index(Request $request)
    {
        $title = "Data Mahasiswa";
        // $dataUser = User::with('unitkerja')->where('level', 'mahasiswa')->get();
        // dd($this->list_mahasiwa(20)->data);
        $dataProdi = $this->list_prodi();

        if (isset($request->prodi)) {
            $angkatan = $request->angkatan;
            $idProdi = $request->prodi;
            $prodinya = array_filter($dataProdi->data, function ($item) use ($idProdi) {
                return $item->id_prodi == $idProdi;
            });
            $prodiSelect = reset($prodinya);
            $dataMahasiswa = User::where("kode_prodi", $request->prodi)
                ->whereRaw("LEFT(username,2) = '$angkatan'")
                ->get();
        } else {
            $dataMahasiswa = User::limit(0)->get();
            $prodiSelect = false;
            $idProdi = false;
            $angkatan = false;
        }


        // dd($dataUser);
        return view('admin.mahasiswa.data', compact(
            'title',
            // 'dataUser',
            'dataMahasiswa',
            'dataProdi',
            'angkatan',
            'idProdi',
            'prodiSelect'
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
        ini_set('max_execution_time', '500');
        $dataMahasiswa = $this->list_mahasiwa($request->angkatan);
        // dd($dataMahasiswa->data);

        foreach ($dataMahasiswa->data as $key) {
            $cekData = User::where("username", $key->npm);
            if ($cekData->count() > 0) {
                User::where("id", $cekData->first()->id)->update([
                    'username' => $key->npm,
                    'name' => $key->nama,
                    'level' => 'mahasiswa',
                    'password' => Hash::make($key->npm),
                    'kode_fakultas' => $key->id_fakultas,
                    'kode_prodi' => $key->id_prodi,
                    'nama_fakultas' => $key->nama_fakultas,
                    'nama_prodi' => $key->nama_prodi,
                    'angkatan' => $request->angkatan,
                ]);
            } else {
                User::create([
                    'username' => $key->npm,
                    'email' => $key->npm . "@teknokrat.ac.id",
                    'name' => $key->nama,
                    'level' => 'mahasiswa',
                    'password' => Hash::make($key->npm),
                    'kode_fakultas' => $key->id_fakultas,
                    'kode_prodi' => $key->id_prodi,
                    'nama_fakultas' => $key->nama_fakultas,
                    'nama_prodi' => $key->nama_prodi,
                    'angkatan' => $request->angkatan,
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
    public function edit($id)
    {
        $title = "Edit Data Mahasiswa";
        $mahasiswa = User::with('unitkerja')->where("id", $id)->first();
        $unitKerja = Unitkerja::all();
        // dd($dataGedung->id);
        $view = view('admin.mahasiswa.update', compact('title', 'mahasiswa', 'unitKerja'));
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
        $dataUser = User::findOrFail($id);

        $validatedData = $request->validate([
            'name'      => 'required|max:255',
            'no_telepon' => 'required',
            // 'foto'      => 'image|file|max:2048',
        ]);
        // dd($validatedData);

        if ($request->password) {
            $validatedData['password'] = Hash::make($request->password);
        }

        if ($request->unitkerja_id) {
            $validatedData['unitkerja_id'] = $request->unitkerja_id;
        }

        // Jika email yang dimasukkan bukan milik pengguna yang sedang diupdate
        if ($request->email != $dataUser->email) {
            // Periksa apakah email sudah terpakai
            $existingEmail = User::where('email', $request->email)->first();

            // Jika email sudah digunakan dan bukan milik pengguna yang sedang diupdate
            if ($existingEmail && $existingEmail->id !== $dataUser->id) {
                return redirect()->back()->withInput()->withErrors(['email' => 'Email sudah terpakai']);
            } else {
                $validatedData['email'] = $request->email;
            }
        }

        // dd($validatedData);

        if ($request->hasFile('foto')) {
            // Validasi tipe file harus image
            $request->validate([
            'foto' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            if ($dataUser->foto) {
            Storage::delete('public/users/' . $dataUser->foto);
            }
            $validatedData['foto'] = $validatedData['name'] . "-" . date('His') . "new." . $request->file('foto')->getClientOriginalExtension();
            $request->file('foto')->storeAs('public/users', $validatedData['foto']);
        }
        $dataUser->update($validatedData);

        return redirect()->route('admin.mahasiswa.index')->with(['msg' => 'Data Berhasil Diubah', 'class' => 'alert-success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $mahasiswa = User::findOrFail($id);
        if ($mahasiswa->foto) {
            Storage::delete('public/users/' . $mahasiswa->foto);
        }
        $mahasiswa->delete();
        return back()->with(['msg' => 'Berhasil Menghapus Mahasiswa', 'class' => 'success']);
    }
}
