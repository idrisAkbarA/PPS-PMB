<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNominalJurusanPerPeriodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nominal_jurusan_per_periodes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('periode_id');
            $table->bigInteger('jurusan_id');
            $table->double('nominal');
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
        Schema::dropIfExists('nominal_jurusan_per_periodes');
    }
}
