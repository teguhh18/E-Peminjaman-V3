<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'prodi';

    public function unitkerja()
    {
        return $this->belongsTo(Unitkerja::class, 'unitkerja_id', 'id');
    }

    public function user()
    {
        return $this->hasMany(User::class);
    }
}
