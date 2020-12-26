<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKatJurusanPerPeriodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kat_jurusan_per_periodes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('periode_id');
            $table->bigInteger('jurusan_id');
            $table->bigInteger('kat_tka_id');
            $table->bigInteger('kat_tkj_id');
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
        Schema::dropIfExists('kat_jurusan_per_periodes');
    }
}
