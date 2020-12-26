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
            $table->bigInteger('jurusan_id');
            $table->bigInteger('user_cln_mhs_id');
            $table->bigInteger('kat_tka_id');
            $table->bigInteger('kat_tkj_id');
            $table->bigInteger('soal_id')->nullable();;
            $table->double('nilai_tka')->nullable();;
            $table->double('nilai_tkj')->nullable();;
            $table->date('start_tkj');
            $table->date('start_tka');
            $table->date('tkj_ended');
            $table->date('tka_ended');
            $table->date('lunas_at')->nullable();;
            $table->date('batas_ujian');
            $table->boolean('is_lulus_tka')->nullable();;
            $table->boolean('is_lulus_tkj')->nullable();;
            $table->date('lulus_at')->nullable();;
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
