<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersetujuanPeminjaman extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'persetujuan_peminjaman';

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function unit_kerja()
    {
        return $this->belongsTo(Unitkerja::class, 'unitkerja_id');
    }

}
