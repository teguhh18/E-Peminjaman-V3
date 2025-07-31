<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Gedung;
use App\Models\Peminjaman;
use App\Models\PeminjamanRuangan;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Auth;
use Hash;
use Carbon\Carbon;
use App\Models\User;
use DateTime;
use Illuminate\Validation\Rule;
use PDF;
use File;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->level != 'mahasiswa') {

            // Jika Bukan level user bukn mahasiswa arahkan dashboard admin
            return redirect()->route('admin.dashboard');
        } else {
            // Jika level user mahasiswa arahkan ke home
            $title = 'List Peminjaman';

            $listPeminjaman = Peminjaman::with(['ruangan', 'user', 'detail_peminjaman'])->where('status_peminjaman', 'disetujui')->get();
            // dd($listPeminjaman);
            return view('mahasiswa.home.index', compact('title', 'listPeminjaman'));
        }
    }


    public function menu()
    {
        $jumalhRuangan = Ruangan::count();
        $jumalhGedung = Gedung::count();
        $jumalhBarang = Barang::count();
        $jml_nominal_aset = Barang::sum('harga_perolehan');
        $data = [
            'jml_ruangan'   => $jumalhRuangan,
            'jml_gedung'   => $jumalhGedung,
            'jml_aset'   => $jumalhBarang,
            'jml_nominal_aset'  => $jml_nominal_aset
        ];
        return $data;
    }

    public function profil()
    {
        $title = "Profil User";

        $user = auth()->user(); // Mengambil pengguna yang sedang login
        // dd($user);

        return view('auth.perbarui-profil', compact(
            'title',
            'user',
        ));
    }

    public function profil_update(Request $request, $id)
    {
        $userId = decrypt($id);
        $dataUser = User::findOrFail($userId);

        // 1. VALIDASI SEMUA INPUT, TERMASUK FILE
        $validatedData = $request->validate([
            'email'       => [
                'required',
                'email',
                Rule::unique('users')->ignore($userId),
            ],
            'no_telepon'  => [
                'required',
                // 'numeric', // Pastikan hanya angka
                Rule::unique('users')->ignore($userId),
            ],
            'foto'        => 'nullable|image|file|max:2048',
            'tanda_tangan' => 'nullable|image|file|max:2048',
        ]);

        // 2. PROSES UPLOAD FOTO (JIKA ADA FILE BARU)
        if ($request->file('foto')) {
            // Hapus foto lama jika ada
            if ($dataUser->foto) {
                Storage::delete('public/users/' . $dataUser->foto);
            }

            // Buat nama file baru dan simpan
            $namaFileFoto = $dataUser->name . "-" . time() . "." . $request->file('foto')->extension();
            $request->file('foto')->storeAs('public/users', $namaFileFoto);

            // Simpan nama file ke array untuk di-update ke database
            $validatedData['foto'] = $namaFileFoto;
        }

        // 3. PROSES UPLOAD TANDA TANGAN (JIKA ADA FILE BARU)
        if ($request->file('tanda_tangan')) {
            // Hapus tanda tangan lama jika ada
            if ($dataUser->tanda_tangan) {
                Storage::delete('public/tanda_tangan/' . $dataUser->tanda_tangan);
            }

            // Buat nama file baru dan simpan
            $namaFileTtd = $dataUser->name . "-ttd-" . time() . "." . $request->file('tanda_tangan')->extension();
            $request->file('tanda_tangan')->storeAs('public/tanda_tangan', $namaFileTtd);

            // Simpan nama file ke array untuk di-update ke database
            $validatedData['tanda_tangan'] = $namaFileTtd; 
        }

        // 4. UPDATE DATABASE
        // Method update akan memperbarui semua data yang ada di dalam array $validatedData
        $dataUser->update($validatedData);

        return redirect()->route('profil')->with(['msg' => 'Data Profil Berhasil Diubah', 'class' => 'alert-success']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {}

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
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function perbarui_password()
    {
        $title = "Perbarui Password";
        return view('auth.perbarui-password', compact('title'));
    }

    public function updatepw(Request $request)
    {

        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error", "Password Lama Salah.");
        }

        if (strcmp($request->get('current-password'), $request->get('new_password')) == 0) {
            //Current password and new password are same
            return redirect()->back()->with("error", "Masukan Password Baru.");
        }
        if (!(strcmp($request->get('new_password'), $request->get('new_password_confirm'))) == 0) {
            //New password and confirm password are not same
            return redirect()->back()->with("error", "Ulangi Password Baru.");
        }

        $user = User::findorfail(Auth::user()->id);
        $user->password = Hash::make($request->get('new_password'));
        $user->save();

        return redirect()->back()->with(['msg' => 'Password Berhasil Diubah', 'class' => 'alert-success']);
    }
}
