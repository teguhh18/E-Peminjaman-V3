<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanRuangan extends Model
{
    use HasFactory;
    protected $table = 'peminjaman_ruangans';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }
}
