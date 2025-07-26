<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // unit kerja
        DB::table('unitkerjas')->insert([
            ['kode' => 'Pustik', 'nama' => 'Pustik'],
            ['kode' => 'KRT', 'nama' => 'Kerumahtanggaan'],
            ['kode' => 'KMS', 'nama' => 'Kemahasiswaan'],
            ['kode' => 'FTIK', 'nama' => 'Fakultas Teknik dan Ilmu Komputer'],
            ['kode' => 'FSIP', 'nama' => 'Fakultas Sastra dan Ilmu Pendidikan'],
            ['kode' => 'FEB', 'nama' => 'Fakultas Ekonomi dan Bisnis'],
            ['kode' => 'PRODI-IF', 'nama' => 'Prodi Informatika'],
            ['kode' => 'PRODI-SI', 'nama' => 'Prodi Sistem Informasi'],
        ]);

        // DB::table('prodi')->insert([
        //     [ 'unitkerja_id' => 4,'kode_prodi' => 'SI', 'nama' => 'S1-Sistem Informasi'],
        //     [ 'unitkerja_id' => 4,'kode_prodi' => 'IF', 'nama' => 'S1-Informatika'],
        //     [ 'unitkerja_id' => 4,'kode_prodi' => 'TI', 'nama' => 'S1-Teknologi Informasi'],
        //     [ 'unitkerja_id' => 5,'kode_prodi' => 'ENG', 'nama' => 'S1-Pendidikan Inggris'],
        //     [ 'unitkerja_id' => 5,'kode_prodi' => 'MTK', 'nama' => 'S1-Pendidikan Matematika'],
        //     [ 'unitkerja_id' => 6,'kode_prodi' => 'AKN', 'nama' => 'S1-Akuntansi'],
        //     [ 'unitkerja_id' => 6,'kode_prodi' => 'MNJ', 'nama' => 'S1-Manajemen'],
            
        // ]);
        
        $faker = Faker::create('id_ID');
        // USER
        DB::table('users')->insert(
            [
                [
                    'username'  => 'admin',
                    'name'  => 'admin',
                    'email' => 'admin@gmail.com',
                    'password'  => bcrypt('rahasia'),
                    'level'  => 'admin',
                    'unitkerja_id' => null,
                    'prodi_id' => null,
                ],
                // [
                //     'username'  => 'mahasiswa',
                //     'name'  => 'mahasiswa',
                //     'email' => 'mahasiswa@gmail.com',
                //     'password'  => bcrypt('rahasia'),
                //     'level'  => 'mahasiswa',
                // ],
                [
                    'username'  => 'pustik',
                    'name'  => $faker->name,
                    'email' => 'pustik@gmail.com',
                    'password'  => bcrypt('rahasia'),
                    'level'  => 'baak',
                    'unitkerja_id' => 1,
                    'prodi_id' => null,
                ],
                [
                    'username'  => 'baak_ftik',
                    'name'  => $faker->name,
                    'email' => 'baak_ftik@gmail.com',
                    'password'  => bcrypt('rahasia'),
                    'level'  => 'baak',
                    'unitkerja_id' => 4,
                    'prodi_id' => null,

                ],
                [
                    'username'  => 'baak_fsip',
                    'name'  => $faker->name,
                    'email' => 'baak_fsip@gmail.com',
                    'password'  => bcrypt('rahasia'),
                    'level'  => 'baak',
                    'unitkerja_id' => 5,
                    'prodi_id' => null,

                ],
                [
                    'username'  => 'baak_feb',
                    'name'  => $faker->name,
                    'email' => 'baak_feb@gmail.com',
                    'password'  => bcrypt('rahasia'),
                    'level'  => 'baak',
                    'unitkerja_id' => 6,
                    'prodi_id' => null,
                ],
                [
                    'username'  => 'kerumahtanggaan',
                    'name'  => $faker->name,
                    'email' => 'krt@gmail.com',
                    'password'  => bcrypt('rahasia'),
                    'level'  => 'kerumahtanggaan',
                    'unitkerja_id' => 2,
                    'prodi_id' => null,
                ],
                [
                    'username'  => 'kaprodi_if',
                    'name'  => $faker->name,
                    'email' => 'kaprodi_if@gmail.com',
                    'password'  => bcrypt('rahasia'),
                    'level'  => 'baak',
                    'unitkerja_id' => 7,
                    'prodi_id' => 2,
                ],
                [
                    'username'  => 'kaprodi_si',
                    'name'  => $faker->name,
                    'email' => 'kaprodi_si@gmail.com',
                    'password'  => bcrypt('rahasia'),
                    'level'  => 'baak',
                    'unitkerja_id' => 8,
                    'prodi_id' => 1,
                ],
                // [
                //     'username'  => 'kaprodi_mtk',
                //     'name'  => $faker->name,
                //     'email' => 'kaprodi_mtk@gmail.com',
                //     'password'  => bcrypt('rahasia'),
                //     'level'  => 'kaprodi',
                //     'unitkerja_id' => 5,
                //     'prodi_id' => 5,
                // ],
            ]
        );

        // mahasiswa
        $usedUsernames = [];
        foreach (range(1, 15) as $index) {
            do {
                $username = $faker->numberBetween(17311001, 24129999);
            } while (in_array($username, $usedUsernames));

            $usedUsernames[] = $username;
            DB::table('users')->insert([
                'unitkerja_id' => null,
                'prodi_id' => random_int(1,7),
                'name' => $faker->name,
                'username' =>  $username,
                'email' => $faker->unique()->safeEmail,
                'email_verified_at' => null,
                'password' => bcrypt('123456'), // Default password, you can change it.
                // 'remember_token' => Str::random(10),
                'level' => 'mahasiswa',
                // 'fakultas_kode' => $faker->randomElement(['FTIK', 'FSIP', 'FEB']),
                'no_telepon' => $faker->phoneNumber,
                'email_pribadi' => $faker->optional()->safeEmail,
                // 'foto' => $faker->image('public/storage/images', 640, 480, null, false)
            ]);
        }

        // kategori
        $kategoris = [
            ['kode' => 'K1', 'nama' => 'Aset Tetap'],
            ['kode' => 'K2', 'nama' => 'Aset Bergerak'],
        ];
        DB::table('kategoris')->insert($kategoris);

        // Gedung
        $prefix = 'Gedung ';
        $letters = ['A', 'B', 'C', 'D', 'ICT'];
        foreach ($letters as $index => $letter) {
            DB::table('gedungs')->insert([
                'kode' => 'G' . $letter, // Kode Gedung, misal GA, GB, GC, ...
                'nama' => $prefix . $letter,
                'lokasi' => 'Lokasi ' . $letter,
                'jumlah_lantai' => $faker->numberBetween(1, 4),
                'tahun' => $faker->year,
                'sumber_dana' => $faker->word,
                'besar_dana' => $faker->randomFloat(2, 100000, 1000000),
                'nilai_residu' => $faker->randomFloat(2, 50000, 500000),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Ruangan
        $arrayKondisi = ['baik','rusak_ringan','rusak_berat'];
        foreach (range(1, 10) as $index) {
            DB::table('ruangans')->insert([
                'kode_ruangan' => $faker->unique()->word,
                'nama_ruangan' => $faker->numberBetween(1, 4) . '0' . $faker->numberBetween(1, 4) . $faker->randomElement($letters),
                'gedung_id' => $faker->numberBetween(1, 4),
                'lantai' => $faker->numberBetween(1, 5),
                'kapasitas' => $faker->numberBetween(10, 100),
                'luas' => $faker->randomFloat(2, 20, 200),
                'tipe' => $faker->word,
                'kondisi' => $faker->randomElement($arrayKondisi),
                'status' => $faker->numberBetween(1, 3), // Assuming status ranges from 1 to 3
                'unitkerja_id' => $faker->numberBetween(1, 6), // Assuming you have 10 unitkerjas
                'bisa_pinjam' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // // Peminjaman
        // DB::table('peminjaman_ruangans')->insert([
        //     [
        //         'user_id' => 2,
        //         'ruangan_id' => 1,
        //         'kegiatan'  => 'Rapat pimpinan',
        //         'no_peminjam'   => '085765842510',
        //         'waktu_pinjam'  => date('Y-m-d H:i:s'),
        //         'waktu_selesai' => date('Y-m-d H:i:s', strtotime('+2 hours')),
        //     ],

        // ]);

        DB::table('peminjaman')->insert([
            [
                'user_id' => 2,
                'ruangan_id' => random_int(1,10),
                'kegiatan'  => 'Rapat pimpinan',
                'no_peminjam'   => '085765842510',
                'waktu_peminjaman'  => date('Y-m-d H:i:s'),
                'waktu_pengembalian' => date('Y-m-d H:i:s', strtotime('+2 hours')),
            ],

        ]);
    }
}
