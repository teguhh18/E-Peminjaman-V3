<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function gedung()
    {
        return $this->belongsTo(Gedung::class, 'gedung_id', 'id');
    }

    public function unitkerja()
    {
        return $this->belongsTo(Unitkerja::class, 'unitkerja_id', 'id');
    }

    public function ruangan()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function pinjam_ruang()
    {
        return $this->belongsTo(PeminjamanRuangan::class);
    }
}
