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
            $table->integer('periode_id');
            $table->integer('jurusan_id');
            $table->integer('user_cln_mhs_id');
            $table->integer('kat_tka_id');
            $table->integer('kat_tkd_id');
            $table->integer('soal_id')->nullable();;
            $table->double('nilai_tka')->nullable();;
            $table->double('nilai_tkd')->nullable();;
            $table->date('start_tkd');
            $table->date('start_tka');
            $table->date('tkd_ended');
            $table->date('tka_ended');
            $table->date('lunas_at')->nullable();;
            $table->date('batas_ujian');
            $table->boolean('is_lulus_tka')->nullable();;
            $table->boolean('is_lulus_tkd')->nullable();;
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
