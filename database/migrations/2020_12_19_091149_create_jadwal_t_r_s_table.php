<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJadwalTRSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jadwal_t_r_s', function (Blueprint $table) {
            $table->id();
            $table->date("tanggal");
            $table->integer("quota");
            $table->string("nama_dosen");
            $table->integer("periode_id");
            $table->json("ids_cln_mhs")->nullable();;
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
        Schema::dropIfExists('jadwal_t_r_s');
    }
}
