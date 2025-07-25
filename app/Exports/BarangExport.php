<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class BarangExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // return Barang::all();
        return collect(Barang::getAllBarang());
    }

    public function headings() :array {
        return [
            // 'Id',
            'Kode',
            'Nama',
            // 'Nama_Kategori',
            'Tanggal_Perolehan',
            // 'Tahun_Perolehan',
            'Lokasi_Barang',
            // 'Nama_Ruangan',
            // 'Nama_Gedung',
            'Penanggung_Jawab',
            'Harga_Perolehan',
            // 'Harga',
            'Jumlah',
            'Kondisi',
            'Status',
            'Deskripsi',
        ];
    }
}
