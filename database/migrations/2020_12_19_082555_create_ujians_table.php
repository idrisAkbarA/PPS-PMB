<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUjiansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ujians', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('periode_id');
            $table->bigInteger('jurusan_id')->nullable();
            $table->bigInteger('user_cln_mhs_id');
            $table->bigInteger('kat_tka_id')->nullable();
            $table->bigInteger('kat_tkj_id')->nullable();
            $table->bigInteger('soal_id')->nullable();
            $table->double('nilai_tka')->nullable();
            $table->double('nilai_tkj')->nullable();
            $table->dateTime('start_tkj')->nullable();
            $table->dateTime('start_tka')->nullable();
            $table->dateTime('tkj_ended')->nullable();
            $table->dateTime('tka_ended')->nullable();
            $table->string('kode_bayar')->nullable();
            $table->date('batas_bayar')->nullable();
            $table->date('lunas_at')->nullable();
            $table->date('batas_ujian')->nullable();
            $table->boolean('is_lulus_tka')->nullable();
            $table->boolean('is_lulus_tkj')->nullable();
            $table->dateTime('lulus_at')->nullable();
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
        Schema::dropIfExists('ujians');
    }
}
