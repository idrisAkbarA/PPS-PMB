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
            $table->json('komposisi_tka_default')->nullable();
            $table->json('komposisi_tkj_default')->nullable();
            $table->integer('kuota_kelas_default')->default(10);
            $table->integer('jalur_masuk_id')->nullable();
            $table->double('nominal_bayar')->nullable();
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
