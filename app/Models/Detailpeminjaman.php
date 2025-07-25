<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detailpeminjaman extends Model
{
    use HasFactory;
    protected $table = 'detailpeminjaman_barangs';
    protected $guarded = [];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function barang()
    {
        return $this->hasOne(Barang::class, 'id', 'barang_id');
    }
}
