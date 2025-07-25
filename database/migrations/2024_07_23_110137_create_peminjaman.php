<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeminjaman extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('user_id')->comment('yang pinjam')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('ruangan_id')->nullable();
            $table->enum('status_ruangan', [
                'disetujui',
                'kunci_diambil',
                'kunci_dikembalikan',
                'bermasalah'
            ])->nullable();
            $table->string('no_peminjam');
            $table->string('kegiatan', 255)->nullable();
            $table->dateTime('waktu_peminjaman');
            $table->dateTime('waktu_pengembalian');
            $table->string('nama_petugas', 255)->nullable()->comment('yang konfirmasi');
            // $table->integer('konfirmasi')->default(1)->comment('1: menunggu, 2: disetujui, 3: Ditolak, 4: aktif, 5: Selesai');
            $table->enum('status_peminjaman', [
                'menunggu',
                'disetujui',
                'ditolak',
                'aktif',
                'selesai',
            ])->default('menunggu');
            $table->string('keterangan', 255)->nullable();
            // $table->string('unit_pustik', 50)->nullable();
            // $table->string('unit_kerumahtanggan', 50)->nullable();
            // $table->date('tgl_pemesanan')->nullable();
            // $table->date('tgl_peminjaman')->nullable();
            // $table->time('jam_peminjaman')->nullable();
            // $table->date('tgl_pengembalian')->nullable();
            // $table->time('jam_pengembalian')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('peminjaman');
    }
}
