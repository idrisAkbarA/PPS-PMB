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
            $table->date('start_tkj')->nullable();
            $table->date('start_tka')->nullable();
            $table->date('tkj_ended')->nullable();
            $table->date('tka_ended')->nullable();
            $table->date('lunas_at')->nullable();
            $table->date('batas_ujian')->nullable();
            $table->boolean('is_lulus_tka')->nullable();
            $table->boolean('is_lulus_tkj')->nullable();
            $table->date('lulus_at')->nullable();
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
