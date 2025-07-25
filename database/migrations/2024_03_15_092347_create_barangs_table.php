<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barangs', function (Blueprint $table) {

            $table->id();
            $table->string('kode', 50)->unique();
            $table->string('nama', 255);
            $table->decimal('harga', 10, 2)->nullable();
            $table->double('jumlah')->nullable();
            // $table->double('tersedia')->nullable();
            $table->unsignedBigInteger('ruangan_id')->nullable();
            $table->unsignedBigInteger('unitkerja_id')->nullable();
            // $table->unsignedBigInteger('kategori_id');
            $table->boolean('bisa_pinjam')->default(false);
            $table->date('tgl_perolehan')->nullable();
            $table->year('tahun_perolehan')->nullable();
            $table->decimal('harga_perolehan', 10, 2)->nullable();
            $table->string('kondisi', 20)->nullable();
            $table->text('deskripsi')->nullable();
            $table->integer('status')->default(1);
            $table->string('foto', 255)->nullable();
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
        Schema::dropIfExists('barangs');
    }
}
