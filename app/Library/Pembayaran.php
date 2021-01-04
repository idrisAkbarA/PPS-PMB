<?php
namespace App\Library;
use Faker\Factory as Faker;
class Pembayaran{
     public function generate($ujian_id)
     {
        //  Generate Kode pembayaran
        $faker = $faker = Faker::create("id_ID");
        return strtoupper($ujian_id . $faker->bothify('?#?#'));
     }
}