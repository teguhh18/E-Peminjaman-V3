<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMahasiswasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('npm_mahasiswa');
            $table->string('nama_mahasiswa')->nullable();
            $table->unsignedBigInteger('kode_program_studi')->nullable();
            $table->string('nama_program_studi')->nullable();
            $table->string('kode_fakultas')->nullable();
            $table->string('nama_fakultas')->nullable();
            $table->string('nama_program_studi_english')->nullable();
            $table->string('nama_fakultas_english')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
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
        Schema::dropIfExists('mahasiswas');
    }
}
