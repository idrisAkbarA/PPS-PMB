<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeriodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periodes', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->date('awal_periode');
            $table->date('akhir_periode');
            $table->bigInteger('range_ujian'); // in days
            $table->bigInteger('durasi_ujian'); // in minutes
            $table->bigInteger('durasi_soal'); // in seconds
            $table->double('syarat_ipk');
            $table->double('syarat_bhs');
            $table->date('awal_temu_ramah');
            $table->date('akhir_temu_ramah');
            // $table->bigInteger('id_jadwal_tr');
            $table->bigInteger('jumlah_tka'); //jumlah soal
            $table->bigInteger('jumlah_tkj');
            $table->bigInteger('min_lulus_tka'); // jumlah minimal soal terjawab benar untuk lulus
            $table->bigInteger('min_lulus_tkj');
            $table->boolean('is_active')->default(0);
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
        Schema::dropIfExists('periodes');
    }
}
