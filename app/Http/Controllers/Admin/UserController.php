<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unitkerja;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Data User";
        $dataUser = User::with('unitkerja')->where('level', '!=', 'mahasiswa')->get();

        // dd($dataUser);
        return view('admin.user.data', compact(
            'title',
            'dataUser'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = "Tambah User";
        $unitKerja = Unitkerja::all();
        return  view('admin.user.create', compact('title', 'unitKerja'));
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
            'name'     => 'required|max:255',
            'username'     => 'required|unique:users',
            'email'     => 'required|unique:users',
            'password'     => 'required|min:8',
            'level'     => 'required',
            'no_telepon'     => 'required',
            'unitkerja_id'     => '',
            'foto' => 'image|file|max:2048'
        ]);
        // dd($request->file('foto')->getExtension());
        // $validatedData['']
        // Tambahkan unitkerja_id ke array validatedData jika level adalah mahasiswa
        if (strtolower($request->level) != 'mahasiswa') {
            $validatedData['unitkerja_id'] = $request->validate([
                'unitkerja_id' => 'required'
            ])['unitkerja_id'];
        }
        if ($request->file('foto')) {
            Storage::makeDirectory('public/users');
            $validatedData['foto'] = $validatedData['username'] . "-" . date('His') . "." . $request->file('foto')->getClientOriginalExtension();
            $request->file('foto')->storeAs('public/users', $validatedData['foto']);
        }

        // $validatedData['foto'] = $validatedData['username'] . "-" . date('His') . "." . $request->file('foto')->getClientOriginalExtension();
        // dd($validatedData['foto']);

        $validatedData['password'] = Hash::make($validatedData['password']);
        User::create($validatedData);
        // dd();
        return redirect()->route('admin.user.index')->with(['msg' => 'User Berhasil Ditambahkan', 'class' => 'alert-success']);
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
        $dataUser = User::where("id", $id)->first();
        $view = view('admin.user.delete', compact('title', 'dataUser'))->render();
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
        $title = "Edit Data User";
        $dataUser = User::with('unitkerja')->where("id", $id)->first();
        $unitKerja = Unitkerja::all();
        // dd($dataGedung->id);
        $view = view('admin.user.update', compact('title', 'dataUser', 'unitKerja'));
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
        // dd($request);
        $dataUser = User::findOrFail($id);

        $validatedData = $request->validate([
            'name'      => 'required|max:255',
            // 'email'     => 'required|email',
            // 'password'  => 'required',
            'level'     => 'required',
            'no_telepon' => 'required',
            // 'unitkerja_id'     => 'required',
            'foto'      => 'image|file|max:2048',
        ]);
        // dd($validatedData);


        if ($request->password) {
            $validatedData['password'] = Hash::make($request->password);
        }

        if ($request->unitkerja_id) {
            $validatedData['unitkerja_id'] = $request->unitkerja_id;
        }

        // Jika username yang dimasukkan bukan milik pengguna yang sedang diupdate
        if ($request->username != $dataUser->username) {
            // Periksa apakah username sudah terpakai
            $existingUser = User::where('username', $request->username)->first();

            // Jika username sudah digunakan dan bukan milik pengguna yang sedang diupdate
            if ($existingUser && $existingUser->id !== $dataUser->id) {
                return redirect()->back()->withInput()->withErrors(['username' => 'Username sudah terpakai']);
            } else {
                $validatedData['username'] = $request->username;
            }
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

        if ($request->file('foto')) {
            if ($dataUser->foto) {
                Storage::delete('public/users/' . $dataUser->foto);
            }
            $validatedData['foto'] = $validatedData['name'] . "-" . date('His') . "new." . $request->file('foto')->getClientOriginalExtension();
            $request->file('foto')->storeAs('public/users', $validatedData['foto']);
        }


        // $validatedData['password'] = Hash::make($validatedData['password']);
        $dataUser->update($validatedData);

        return redirect()->route('admin.user.index')->with(['msg' => 'Data Berhasil Diubah', 'class' => 'alert-success']);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->foto) {
            Storage::delete('public/users/' . $user->foto);
        }
        User::where("id", $user->id)->delete();
        return back()->with(['msg' => 'Berhasil Menghapus User', 'class' => 'success']);
    }

    public function list_mahasiwa()
    {
        $title = "Data Mahasiswa";
        $uriProdi = env('URL_SISFO') . '/api/programstudi.php?id_prodi=ALL';
        $dataProdi = json_decode(file_get_contents($uriProdi));
    }

    public function import_mahasiwa()
    {
        // 
    }
}
