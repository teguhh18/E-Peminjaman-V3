<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\PeminjamanRuangan;
use App\Models\Ruangan;
use DateTime;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = 'Jadwal';
        $events = [];
        $ruangan = Ruangan::all();
        if (isset($request->ruangan_id)) {
            $appointments = Peminjaman::with(['user', 'ruangan'])->whereNotNull('ruangan_id')->where('konfirmasi', 2)->where('ruangan_id', $request->ruangan_id)->get();

            $ruangan_id = $request->ruangan_id;
        } else {
            $appointments = Peminjaman::with(['user', 'ruangan'])->whereNotNull('ruangan_id')->where('konfirmasi', 2)->get();
            $ruangan_id = null;
        }
        
        foreach ($appointments as $appointment) {

            $start = new DateTime($appointment->waktu_peminjaman);
            $end = new DateTime($appointment->waktu_pengembalian);

            // Format waktu ke format ISO8601
            $startIso = $start->format('Y-m-d\TH:i:s');
            $endIso = $end->format('Y-m-d\TH:i:s');

            $events[] = [
                'title' => $appointment->kegiatan,
                'start' => $startIso,
                'end' => $endIso,
                'extendedProps' => [
                    'ruangan' => $appointment->ruangan->nama_ruangan,
                    'mahasiswa' => $appointment->user->name,
                ],
            ];
        }

        return view('admin.jadwal.index', compact('title', 'events', 'ruangan', 'ruangan_id'));
    }
    
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
}
