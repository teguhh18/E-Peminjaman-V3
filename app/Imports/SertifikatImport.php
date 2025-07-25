<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\Sertifikat;
use App\Models\Sertifikatdetail;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SertifikatImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    private $data;

    public function __construct(array $data = []){
        $this->data = $data;
    }

    public function collection(Collection $rows)
    {
        $cekSertifikat = Sertifikat::
            where('penghargaan_id',  $this->data['penghargaan'])
            ->where('tanggal_sertifikat', $this->data['tanggal'])
            ->where('nama_matakuliah', $this->data['nama_matakuliah'])
            ->where('semester', $this->data['semester'])
            ->where('detail_kegiatan_id', $this->data['kegiatan'])
            ->where('nama_fakultas', $this->data['nama_fakultas'])
            ->first();

        if(!is_null($cekSertifikat)) {
            $id_sertifikat = $cekSertifikat->id;
        } else {
            $sertifikat = Sertifikat::create([
                'tanggal_sertifikat' => $this->data['tanggal'] ?? '',
                'detail_kegiatan_id' => $this->data['kegiatan'] ?? '',
                'penghargaan_id' => $this->data['penghargaan'] ?? '',
                'semester' => $this->data['semester'] ?? '',
                'nama_fakultas' => $this->data['nama_fakultas'] ?? '',
                'nama_matakuliah' => $this->data['nama_matakuliah'] ?? '',
                'keterangan_penghargaan' => $this->data['keterangan_penghargaan'] ?? '',
                'keterangan_sertifikat' => $this->data['keterangan_sertifikat'] ?? '',
            ]);
            $id_sertifikat = $sertifikat->id;
        }

        foreach ($rows as $row) {
            $mhs = Mahasiswa::where("npm",$row['id_peserta'])->first();
            $prodiEnglish = $mhs->nama_program_studi_english ?? '';
            $nomor_sertifikat = $row['nomor_sertifikat'].''.$row['kode_sertifikat'];

            $cekSertifikatDetail = Sertifikatdetail::
                where('nomor_sertifikat', $nomor_sertifikat)
                ->where('peserta_id', $row['id_peserta'])
                ->where('penghargaan_id',  $this->data['penghargaan'])
                ->where('tanggal_sertifikat', $this->data['tanggal'])
                ->where('detail_kegiatan_id', $this->data['kegiatan'])
                ->where('semester', $this->data['semester'])
                ->first();
                
            if (!is_null($cekSertifikatDetail)) {
                $cekSertifikatDetail->update([
                    'sertifikat_id' => $id_sertifikat,
                    'tanggal_sertifikat' => $this->data['tanggal'] ?? '',
                    'nomor_sertifikat' => $nomor_sertifikat ?? '',
                    'peserta_id' => $row['id_peserta'] ?? '',
                    'nama_peserta' => $row['nama_mahasiswa'] ?? '',
                    'program_studi' => $prodiEnglish,
                    'link_sertifikat' => $row['link_sertifikat'] ?? '',
                    'fakultas' => $row['fakultas'] ?? '',
                    'detail_kegiatan_id' => $this->data['kegiatan'] ?? '',
                    'penghargaan_id' => $this->data['penghargaan'] ?? '',
                    'semester' => $this->data['semester'] ?? '',
                ]);
            } else {
                Sertifikatdetail::create([
                    'sertifikat_id' => $id_sertifikat,
                    'tanggal_sertifikat' => $this->data['tanggal'] ?? '',
                    'nomor_sertifikat' => $nomor_sertifikat ?? '',
                    'peserta_id' => $row['id_peserta'] ?? '',
                    'nama_peserta' => $row['nama_mahasiswa'] ?? '',
                    'program_studi' => $prodiEnglish,,
                    'link_sertifikat' => $row['link_sertifikat'] ?? '',
                    'fakultas' => $row['fakultas'] ?? '',
                    'detail_kegiatan_id' => $this->data['kegiatan'] ?? '',
                    'penghargaan_id' => $this->data['penghargaan'] ?? '',
                    'semester' => $this->data['semester'] ?? '',
                ]);
            }
        }
    }

    public function startRow(): int
    {
        return 2;
    }
}
