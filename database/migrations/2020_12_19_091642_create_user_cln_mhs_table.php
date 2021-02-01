<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserClnMhsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_cln_mhs', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->string('hp')->nullable();
            $table->string('wa')->nullable();
            $table->string('alamat')->nullable();
            $table->string('ijazah')->nullable();
            $table->string('nilai_ipk')->nullable();
            $table->string('nilai_bhs_inggris')->nullable();
            $table->string('nilai_bhs_arab')->nullable();
            $table->string('pas_photo')->nullable();
            $table->timestamp('is_verified')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('user_cln_mhs');
    }
}
