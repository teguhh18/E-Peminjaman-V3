<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unitkerja extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function ruangan()
    {
        return $this->hasMany(Ruangan::class);
    }

    public function user()
    {
        return $this->hasMany(User::class);
    }


    public function prodi()
    {
        return $this->hasMany(Prodi::class);
    }
}
