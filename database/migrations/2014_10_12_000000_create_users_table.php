<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('unitkerja_id')->nullable();
            $table->unsignedBigInteger('prodi_id')->nullable();
            $table->string('name', 255);
            $table->string('username', 255)->unique();
            $table->string('email', 255)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255);
            $table->string('remember_token', 100)->nullable();
            $table->enum('level', ['admin', 'baak', 'mahasiswa','kerumahtanggaan','kaprodi']);
            $table->string('fakultas_kode', 255)->nullable();
            $table->string('no_telepon', 255)->nullable();
            $table->string('email_pribadi', 255)->nullable();
            $table->string('foto', 255)->nullable();
            $table->string('tanda_tangan', 255)->nullable();
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
        Schema::dropIfExists('users');
    }
}
