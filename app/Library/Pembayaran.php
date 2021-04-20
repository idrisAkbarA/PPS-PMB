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

      $urutan = 1;
      $periode_number = self::appendZeroes($ujian->periode_id, 3);
      $jurusan_number = self::appendZeroes($ujian->jurusan_id, 3);
      $urutan_number = self::appendZeroes(count($ujianHasKodeBayar) + $urutan, 4);
      $payment_number = '991' . $periode_number . $jurusan_number . $urutan_number;
      $is_payment_number_valid = false;
      // do check if the generated payment number is valid
      while (!$is_payment_number_valid) {
         $payment = Ujian::where(['kode_bayar' => $payment_number])->first();
         if (!$payment) {
            // if payment with the generated number isn't exist yet,
            // set the payment number
            $is_payment_number_valid = true;
         } else {
            // if the payment exist, then add 1 to urutan
            $urutan += 1;
            $urutan_number = self::appendZeroes(count($ujianHasKodeBayar) + $urutan, 4);
            $payment_number = '991' . $periode_number . $jurusan_number . $urutan_number;
         }
      }
      return $payment_number;
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
   public function reset($idUjian)
   {
      $ujian = Ujian::find($idUjian);
      $ujian->is_lunas = false;
      $ujian->lunas_at = null;
      $ujian->save();
   }
}
