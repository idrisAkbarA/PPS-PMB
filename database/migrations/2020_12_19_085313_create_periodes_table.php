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
            $table->integer('id_kat_tka');
            $table->integer('id_kat_tkd');
            $table->integer('range_ujian'); // in days
            $table->double('syarat_ipk');
            $table->double('syarat_bhs');
            $table->date('awal_temu_ramah');
            $table->date('akhir_temu_ramah');
            // $table->integer('id_jadwal_tr');
            $table->integer('jumlah_tka'); //jumlah soal
            $table->integer('jumlah_tkd');
            $table->boolean('is_active');
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
