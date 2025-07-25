<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFakultasProdiToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('kode_fakultas')->nullable();
            $table->string('nama_fakultas')->nullable();
            $table->string('kode_prodi')->nullable();
            $table->string('nama_prodi')->nullable();
            $table->year('angkatan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('kode_fakultas');
            $table->dropColumn('nama_fakultas');
            $table->dropColumn('kode_prodi');
            $table->dropColumn('nama_prodi');
            $table->dropColumn('angkatan');
        });
    }
}
