<?php

namespace App\Observers;

use App\Models\Peminjaman;
use App\Models\PersetujuanPeminjaman;

class PeminjamanObserver
{
    /**
     * Handle the Peminjaman "created" event.
     *
     * @param  \App\Models\Peminjaman  $peminjaman
     * @return void
     */
    public function created(Peminjaman $peminjaman): void {
        $jabatanApprovers = ['kaprodi', 'kerumahtanggaan', 'tu_dosen'];
        foreach ($jabatanApprovers as $jabatan) {
            PersetujuanPeminjaman::create([
                'peminjaman_id' => $peminjaman->id,
                'jabatan_approver' => $jabatan,
            ]);
        }
    }

    /**
     * Handle the Peminjaman "updated" event.
     *
     * @param  \App\Models\Peminjaman  $peminjaman
     * @return void
     */
    public function updated(Peminjaman $peminjaman)
    {
        //
    }

    /**
     * Handle the Peminjaman "deleted" event.
     *
     * @param  \App\Models\Peminjaman  $peminjaman
     * @return void
     */
    public function deleted(Peminjaman $peminjaman)
    {
        //
    }

    /**
     * Handle the Peminjaman "restored" event.
     *
     * @param  \App\Models\Peminjaman  $peminjaman
     * @return void
     */
    public function restored(Peminjaman $peminjaman)
    {
        //
    }

    /**
     * Handle the Peminjaman "force deleted" event.
     *
     * @param  \App\Models\Peminjaman  $peminjaman
     * @return void
     */
    public function forceDeleted(Peminjaman $peminjaman)
    {
        //
    }
}
