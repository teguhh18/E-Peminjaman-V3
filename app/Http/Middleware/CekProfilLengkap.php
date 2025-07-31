<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CekProfilLengkap
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Cek jika user sudah login DAN profilnya belum lengkap
        if ($user && (empty($user->no_telepon) || empty($user->tanda_tangan))) {

            // Izinkan akses HANYA ke halaman update profil dan proses logout
            if (!$request->routeIs('profil') && !$request->routeIs('profil_update') && !$request->routeIs('logout')) {

                // Jika mencoba akses halaman lain, paksa redirect ke halaman profil
                return redirect()->route('profil')->with(['msg' => 'Harap lengkapi Nomor Telepon dan Tanda Tangan Anda terlebih dahulu.', 'class' => 'alert-warning']);
                
            }
        }
        return $next($request);
    }
}
