<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJurusansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jurusans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kode_jurusan')->nullable();
            $table->json('komposisi_tka_default')->nullable();
            $table->json('komposisi_tkj_default')->nullable();
            $table->integer('kuota_kelas_default')->default(10);
            $table->integer('kode_jalur_masuk')->nullable();
            $table->double('nominal_bayar_default')->nullable();
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
        Schema::dropIfExists('jurusans');
    }
}
