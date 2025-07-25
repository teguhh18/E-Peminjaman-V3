<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailpeminjamanBarangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detailpeminjaman_barangs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('peminjaman_id');
            $table->unsignedBigInteger('barang_id');
            $table->integer('jml_barang')->nullable();
            $table->enum('status', [
                'disetujui',
                'diambil',
                'proses_pengembalian',
                'dikembalikan',
                'bermasalah'
                ])->nullable();
            $table->string('catatan')->nullable();
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
        Schema::dropIfExists('detailpeminjaman_barangs');
    }
}
