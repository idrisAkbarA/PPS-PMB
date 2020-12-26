<?php

namespace App\Library;

use App\Soal;
use App\BankSoal;
use App\Jurusan;

class SoalUjian
{
    public $id = null;
    public function generate($jurusan_id, $kat_tka_id, $kat_tkj_id, $jumlah_tka, $jumlah_tkj)
    {
        $final_soal = [];
        //get both tka and tkj questions from banksoal
        $soalTKA = BankSoal::where([
            'type' => 'tka',
            'jurusan_id' => $jurusan_id,
            'kategori_id' => $kat_tka_id
        ])->get();
        $soalTKJ = BankSoal::where([
            'type' => 'tkj',
            'jurusan_id' => $jurusan_id,
            'kategori_id' => $kat_tkj_id
        ])->get();

        // select the questions randomly until it met requested quantity 
        $tka_selected = self::selectSoal($soalTKA, $jumlah_tka);
        $tkj_selected = self::selectSoal($soalTKJ, $jumlah_tkj);

        $final_soal['tka'] = $tka_selected;
        $final_soal['tkj'] = $tkj_selected;
        //store to db
        $soalInstance = new Soal;
        $soalInstance->set_pertanyaan = json_encode($final_soal);
        $soalInstance->save();
        // self::$id = $soalInstance->id;
    }
    private function selectSoal($soal, $jumlah)
    {
        $soal_id_listed = []; //  store the id that've randomly selected
        $soal_merged = []; // the questions goes here

        // loop as much as jumlah to set the questions
        while (count($soal_id_listed) < $jumlah) {
            $randomNumber = rand(0, count($soal) - 1);
            $isTaken = false;
            //loop to check if random number already selected or not
            foreach ($soal_id_listed as $key => $value) {
                if ($value == $randomNumber) {
                    $isTaken = true;
                    break;
                }
            }
            // add the question to array if the id haven't selected before
            if (!$isTaken) {
                $tempSoal = $soal[$randomNumber];
                array_push($soal_merged, $tempSoal);
                array_push($soal_id_listed, $randomNumber);
                // echo 'soal selected with index ' . $randomNumber . " | soal created: " . count($soal_id_listed) . "\n";
            }
        }
        return $soal_merged;
    }
}
