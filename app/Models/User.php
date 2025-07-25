<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'level',
        'fakultas_kode',
        'email_pribadi',
        'no_telepon',
        'unitkerja_id',
        'foto',
        'tanda_tangan',
        'kode_fakultas',
        'kode_prodi',
        'nama_fakultas',
        'nama_prodi',
        'angkatan',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'user_id', "id");
    }

    public function peminjaman_barang()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function peminjaman_ruang()
    {
        return $this->hasMany(PeminjamanRuangan::class);
    }

    public function unitkerja()
    {
        return $this->belongsTo(Unitkerja::class);
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id', "id");
    }


}
