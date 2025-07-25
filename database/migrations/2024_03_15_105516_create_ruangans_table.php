<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRuangansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ruangans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_ruangan', 50)->unique();
            $table->string('nama_ruangan', 255);
            $table->unsignedBigInteger('gedung_id')->nullable();
            $table->string('lantai', 50);
            $table->integer('kapasitas');
            $table->decimal('luas', 10, 2)->nullable();
            $table->string('tipe', 50)->nullable();
            $table->enum('kondisi', [
                'baik',
                'rusak_berat',
                'rusak_ringan',
            ])->default('baik');
            $table->integer('status')->default(1);
            $table->unsignedBigInteger('unitkerja_id')->nullable();
            $table->boolean('bisa_pinjam')->default(false);
            $table->string('foto_ruangan', 255)->nullable();
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
        Schema::dropIfExists('ruangans');
    }
}
