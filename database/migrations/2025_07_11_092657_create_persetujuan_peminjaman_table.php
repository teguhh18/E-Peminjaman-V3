<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersetujuanPeminjamanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('persetujuan_peminjaman', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('peminjaman_id');
            $table->unsignedBigInteger('unitkerja_id');
        //    $table->enum('approval_role', ['baak', 'kerumahtanggan', 'kaprodi']);
            $table->unsignedBigInteger('approver_id')->nullable(); //relasi user_id tabel Users
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->text('catatan')->nullable();
            $table->timestamp('tanggal_aksi')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('persetujuan_peminjaman');
    }
}
