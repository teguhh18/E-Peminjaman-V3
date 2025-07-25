<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;
use Illuminate\Support\Facades\DB;

use App\Models\Presensi;
use Carbon\Carbon;
use Auth;

class Helper {

    public function checkAbsen($id, $i, $month) {
        $presensi = Presensi::where('user_id', $id)
            ->whereMonth('tanggal_presensi', '=', Carbon::parse($month)->format('m'))
            ->whereDay('tanggal_presensi', '=', $i)
            ->first();
        return 
        $presensi;
    }
    
    public function checkAbsenCount($id, $month) {
        $presensi = Presensi::where('user_id', $id)
            ->whereMonth('tanggal_presensi', '=', Carbon::parse($month)->format('m'))
            ->count();
        return $presensi;
    }

    public function checkAbsenTotalTime($id, $month) {
        $presensi = Presensi::where('user_id', $id)
            ->whereMonth('tanggal_presensi', '=', Carbon::parse($month)->format('m'))
            ->sum('time');
        return $presensi;
    }

    // new update
    public function checkAbsenTanggal($id, $tanggal) {
        $presensi = Presensi::where('user_id', $id)
            ->where('tanggal_presensi', '=', $tanggal)
            ->first();
        return $presensi;
    }

    public function checkAbsenTotalTimeTanggal($id, $dari, $sampai) {
        $presensi = Presensi::where('user_id', $id)
            ->whereBetween('tanggal_presensi', [$dari, $sampai])
            ->sum('time');
        return $presensi;
    }

    public function checkAbsenCountTanggal($id, $dari, $sampai) {
        $presensi = Presensi::where('user_id', $id)
        ->whereBetween('tanggal_presensi', [$dari, $sampai])
            ->count();
        return $presensi;
    }

    public function checkAbsenCountTanggalHadir($id, $dari, $sampai) {
        $presensi = Presensi::where('user_id', $id)
            ->where('status', 'hadir')
            ->whereBetween('tanggal_presensi', [$dari, $sampai])
            ->count();
        return $presensi;
    }

    public function checkAbsenCountTanggalSakit($id, $dari, $sampai) {
        $presensi = Presensi::where('user_id', $id)
            ->where('status', 'sakit')
            ->whereBetween('tanggal_presensi', [$dari, $sampai])
            ->count();
        return $presensi;
    }

    public function checkAbsenCountTanggalIzin($id, $dari, $sampai) {
        $presensi = Presensi::where('user_id', $id)
            ->where('status', 'izin')
            ->whereBetween('tanggal_presensi', [$dari, $sampai])
            ->count();
        return $presensi;
    }

    public function checkAbsenCountTanggalCuti($id, $dari, $sampai) {
        $presensi = Presensi::where('user_id', $id)
            ->where('status', 'cuti')
            ->whereBetween('tanggal_presensi', [$dari, $sampai])
            ->count();
        return $presensi;
    }
    
    public function checkAbsenCountTanggalDinasluar($id, $dari, $sampai) {
        $presensi = Presensi::where('user_id', $id)
            ->where('status', 'dinas luar')
            ->whereBetween('tanggal_presensi', [$dari, $sampai])
            ->count();
        return $presensi;
    }
}