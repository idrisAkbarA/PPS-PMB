<?php

namespace App\Library;

use App\Ujian;
use Faker\Factory as Faker;

class Pembayaran
{

   public function generate($ujian_id)
   {
      //  Generate Kode pembayaran
      // format    -> 991-ppp-jjj-uuuu
      // example   -> 9910010010001

      // 991       -> kode dari bank
      // p         -> periode id
      // j         -> jurusan id
      // u         -> urutan pembayaran
      $ujian = Ujian::find($ujian_id);
      $ujianHasKodeBayar = Ujian::where([
         'periode_id' => $ujian->periode_id,
         'jurusan_id' => $ujian->jurusan_id
      ])
         ->whereNotNull('kode_bayar')
         ->get();

      $periode_number = self::appendZeroes($ujian->periode_id, 3);
      $jurusan_number = self::appendZeroes($ujian->jurusan_id, 3);
      $urutan_number = self::appendZeroes(count($ujianHasKodeBayar) + 1, 4);
      return '991' . $periode_number . $jurusan_number . $urutan_number;
      //    old code
      //    $faker = $faker = Faker::create("id_ID");
      //    return strtoupper($ujian_id . $faker->bothify('?#?#'));
   }
   public function appendZeroes($value, $digit)
   {
      // append zeroes to fit the format
      // eg: digits -> 4, value -> '1', result -> 0001
      $value = (string) $value;
      while (strlen($value) < $digit) {
         $value = '0' . $value;
      }
      return $value;
   }
}
