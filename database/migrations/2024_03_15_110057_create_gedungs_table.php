<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGedungsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gedungs', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 50)->unique()->nullable(); // Unique identifier for the building
            $table->string('nama', 255); // Name of the building
            $table->string('lokasi', 255)->nullable(); // Address of the building
            $table->integer('jumlah_lantai'); // Number of floors in the building
            $table->string('foto')->nullable(); // Path or URL to the building's photo (nullable)
            $table->year('tahun')->nullable(); // Path or URL to the building's photo (nullable)
            $table->string('sumber_dana')->nullable(); // Path or URL to the building's photo (nullable)
            $table->decimal('besar_dana', 20, 2)->nullable(); // Path or URL to the building's photo (nullable)
            $table->decimal('nilai_residu', 20, 2)->nullable(); // Path or URL to the building's photo (nullable)

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
        Schema::dropIfExists('gedungs');
    }
}
