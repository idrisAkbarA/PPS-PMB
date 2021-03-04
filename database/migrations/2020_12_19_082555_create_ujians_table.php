<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Unique;

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
            // $table->string('');
            $table->string('ujian_id')->nullable();
            $table->bigInteger('periode_id');
            $table->bigInteger('jurusan_id')->nullable();
            $table->bigInteger('user_cln_mhs_id');
            $table->boolean('is_agree')->default(false);
            $table->json('komposisi_tka')->nullable();
            $table->json('komposisi_tkj')->nullable();
            $table->bigInteger('soal_id')->nullable();
            $table->double('nilai_tka')->nullable();
            $table->double('nilai_tkj')->nullable();
            $table->timestamp('start_tkj')->nullable();
            $table->timestamp('start_tka')->nullable();
            $table->timestamp('tkj_ended')->nullable();
            $table->timestamp('tka_ended')->nullable();
            $table->string('kode_bayar')->unique()->nullable();
            $table->date('batas_bayar')->nullable();
            $table->date('lunas_at')->nullable();
            $table->boolean('is_lunas')->nullable();
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
