<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class Barang extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }
    public function barang()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function unitkerja()
    {
        return $this->belongsTo(Unitkerja::class, 'unitkerja_id', 'id');
    }

    public static function getAllBarang()
    {
        // $dataBarang = Barang::with('ruangan.gedung')->get();
        $dataBarang = Barang::with(['ruangan.gedung'])->get();
        $result = $dataBarang->map(function($barang) {
            $kondisiMapping = [
                1 => 'Baik',
                2 => 'Rusak Berat',
                3 => 'Rusak Ringan'
            ];
            
            // Konversi nilai status
            $statusMapping = [
                1 => 'Aktif',
                2 => 'Dihapus',
                3 => 'Diperbaiki'
            ];

            return [
                'kode' => $barang->kode,
                'nama' => $barang->nama,
                // 'nama_kategori' => $barang->kategori->nama,
                'tanggal_perolehan' => $barang->tgl_perolehan,
                // 'tahun_perolehan' => $barang->tahun_perolehan,
                'lokasi_barang' => $barang->ruangan->nama_ruangan.', '.$barang->ruangan->gedung->nama ,
                // 'nama_ruangan' => $barang->ruangan->nama_ruangan,
                // 'nama_gedung' => $barang->ruangan->gedung->nama,
                'penanggung_jawab' => $barang->penanggung_jawab,
                'harga_perolehan' => $barang->harga_perolehan,
                'jumlah' => $barang->jumlah,
                'kondisi' => $kondisiMapping[$barang->kondisi] ?? 'Tidak Diketahui',
                'status' => $statusMapping[$barang->status] ?? 'Tidak Diketahui',
                'deskripsi' => $barang->deskripsi,
            ];
        })->toArray();
        // $result = DB::table('barangs')->select('id','kode','nama','harga','jumlah','penanggung_jawab','tgl_perolehan','tahun_perolehan','harga_perolehan','deskripsi')->get()->toArray();

        return $result;
    }
}
