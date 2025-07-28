<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;
    protected $table = 'peminjaman';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detail_peminjaman()
    {
        return $this->hasMany(Detailpeminjaman::class, 'peminjaman_id');
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }

    public function persetujuan_peminjaman()
    {
        return $this->hasMany(PersetujuanPeminjaman::class, 'peminjaman_id');
    }

    // app/Models/Peminjaman.php

    public function barangs()
    {
        return $this->belongsToMany(Barang::class, 'detailpeminjaman_barangs')
            ->withPivot('jml_barang') // Sertakan kolom tambahan dari tabel pivot
            ->withTimestamps();
    }
}
