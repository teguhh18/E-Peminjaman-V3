<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        $barang = [
            'Mic', 'Speaker', 'Sound System', 'Baterai', 'Televisi', 'Radio',
            'Laptop', 'Proyektor', 'Printer', 'Scanner', 'Kamera',
            'Headphone', 'Mouse', 'Keyboard', 'Monitor', 'Hard Disk Eksternal',
            'USB Flash Drive', 'Charger', 'Power Bank', 'Router'
        ];
        $randomKey = array_rand($barang);
        DB::table('barangs')->insert(
            [
                [
                    'kode'  => 'B-0001',
                    'nama'  => 'Barang 1',
                    'jumlah'  => 1,
                    'harga'  => 1000,
                    'deskripsi' => 'test',
                    // 'kategori_id'  => 1,
                    'ruangan_id'  => 1,
                ],
            ]
        );

        for ($i = 2; $i <= 10; $i++) {
            DB::table('barangs')->insert([
                'kode' => 'B-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                // 'nama' => $barang[rand(0, count($barang))],
                'nama' => $faker->randomElement($barang),
                'harga' => $faker->randomFloat(2, 100, 10000),
                'jumlah' => $faker->randomNumber(1, 5),
                'bisa_pinjam' => 1,
                // 'tersedia' => $faker->randomElement([0, 1]),
                'ruangan_id' => $faker->numberBetween(1, 10),
                // 'kategori_id' => $faker->numberBetween(1, 2),
                'unitkerja_id' => $faker->numberBetween(1, 6),
                'tgl_perolehan' => $faker->date,
                'tahun_perolehan' => $faker->year,
                'harga_perolehan' => $faker->randomFloat(2, 10000, 10000000),
                'kondisi' => $faker->randomElement(['baik', 'rusak', 'perbaikan']),
                'deskripsi' => $faker->text,
                'status' => $faker->numberBetween(1, 3),
                'foto' => 'image.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
