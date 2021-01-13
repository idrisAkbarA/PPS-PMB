<?php

namespace App\Library;

use App\Soal;
use App\BankSoal;
use App\Jurusan;
use App\Ujian;
use App\Periode;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class SoalUjian
{
    public $id = null;
    public function generate($jurusan_id, $kat_tka_id, $kat_tkj_id, $jumlah_tka, $jumlah_tkj, $ujian_id)
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
        $tka_selected = self::selectRandomly($soalTKA, $jumlah_tka);
        $tkj_selected = self::selectRandomly($soalTKJ, $jumlah_tkj);

        $final_soal = [
            ["type" => "tka", "soal" => $tka_selected],
            ["type" => "tkj", "soal" => $tkj_selected],
        ];

        //store to db
        $soalInstance = new Soal;
        $soalInstance->set_pertanyaan = $final_soal;
        $soalInstance->save();
        $this->id = $soalInstance->id;
        // dd($soalInstance->id);
        $ujianInstance = Ujian::find($ujian_id);
        // dd($ujian_id);
        // calculate ujian deadline
        // $batas_ujian = self::calcDeadline($ujian_id);
        // return $batas_ujian;
        // update ujian
        $ujianInstance->soal_id = $soalInstance->id;
        // $ujianInstance->batas_ujian = $batas_ujian;
        $ujianInstance->save();
    }
    public function calcDeadline($ujian_id)
    {
        $ujianInstance = Ujian::find($ujian_id);

        $periode_id =  $ujianInstance['periode_id'];
        $periode = Periode::find($periode_id);
        // return $periode;
        $range_ujian = $periode->range_ujian;
        $akhir_periode = Carbon::createFromFormat('Y-m-d', $periode->akhir_periode);
        $temp_batas_ujian = Carbon::now()->addDays($range_ujian);
        $selisih = $temp_batas_ujian->diffInDays($akhir_periode, false);
        // $selisih = $akhir_periode->diffInDays($temp_batas_ujian, false);
        if ($selisih >= 0) {
            $batas_ujian = $temp_batas_ujian;
        } else {
            $batas_ujian = $akhir_periode;
        }
        return $batas_ujian;
        // return [$akhir_periode, $temp_batas_ujian, $selisih];
        // return $selisih;
        // return [$akhir_periode, $temp_batas_ujian];
        // return $akhir_periode;
    }
    public function get($type, $id)
    {
        // this function send soal to client according to the requested type (tka or tkj)
        // and prevent 'kunci jawaban' being sent to the client
        $soal = Soal::find($id);
        $soal_collection = collect($soal->set_pertanyaan);
        $jawaban_collection = collect($soal->set_jawaban_mhs);
        $result_jawaban = $jawaban_collection->where('type', $type)->first();
        $result_soal = $soal_collection->where('type', $type)->first();
        foreach ($result_soal->soal as $key => $value) {
            unset($value->jawaban);
        }
        return ['soal' => $result_soal->soal, 'jawaban' => $result_jawaban->jawaban ?? null];
    }
    public function setJawaban($type, $rowID, $soalID, $jawaban)
    {
        //this functiion store or update cln mahasiswa's answer


        $soal = Soal::find($rowID);
        $jawabanDB = $soal->set_jawaban_mhs;
        //answer structure to be stored
        $setJawaban = ['id' => $soalID, 'jawaban' => $jawaban];

        // check if it is the first answer made by cln mhs or not
        if (!$jawabanDB) {
            //set answer for the first time
            $soal->set_jawaban_mhs = [['type' => $type, 'jawaban' => [$setJawaban]]];
            $soal->save();
            return ['status' => true, 'message' => 'First Jawaban received by server'];
        }
        // initialize check value
        $isTypeSet = false;
        $typeIndex = null;
        foreach ($jawabanDB as $key => $value) {
            if ($value->type == $type) {
                $isTypeSet = true;
                $typeIndex = $key;
            }
        }

        // if it's type is not set then make one
        if (!$isTypeSet) {
            array_push($jawabanDB, ['type' => $type, 'jawaban' => [$setJawaban]]);
            $soal->set_jawaban_mhs = $jawabanDB;
            $soal->save();
            return ['status' => true, 'message' => 'new Jawaban type received by server'];
        }

        // if it's type is set before then check if it is an
        // update to an already answered question
        if ($isTypeSet) {
            foreach ($jawabanDB[$typeIndex]->jawaban as $key => $value) {
                if ($value->id == $soalID) {
                    //update the answer
                    $jawabanDB[$typeIndex]->jawaban[$key]->jawaban = $jawaban;
                    $soal->set_jawaban_mhs = $jawabanDB;
                    $soal->save();
                    return ['status' => true, 'message' => 'Updated Jawaban received by server'];
                }
            }
        }

        // add new answer
        array_push($jawabanDB[$typeIndex]->jawaban, $setJawaban);
        $soal->set_jawaban_mhs = $jawabanDB;
        $soal->save();
        return ['status' => true, 'message' => 'New Jawaban received by server'];
    }
    public function setLulus($idUjian, $type)
    {
        $periodeObject = 'min_lulus_' . $type;
        $nilaiObject = 'nilai_' . $type;
        $isLulusObject = 'is_lulus_' . $type;
        $ujian = Ujian::find($idUjian);
        $periode = Periode::find($ujian->periode_id);

        $batasLulus = $periode->$periodeObject;
        $nilaiUjian = $ujian->$nilaiObject;
        $isLulus = $nilaiUjian >= $batasLulus;

        $ujian->$isLulusObject = $isLulus;
        $ujian->save();
        return ['nilai' => $nilaiUjian, 'batas_lulus' => $batasLulus, 'status_lulus' => $isLulus];
        return ['status lulus' => $isLulus];
    }
    public function calcScore($id, $type)
    {
        // kalkulasi nilai
        // mencocokkan antara kunci jawaban di set_pertanyaan dengan set_jawaban_mhs
        $score = 0;
        $instance = Soal::find($id);
        $soalDB =  $instance->set_pertanyaan;
        $jawabanDB = $instance->set_jawaban_mhs;
        $soalTypeIndex = null;
        $jawabanTypeIndex = null;
        foreach ($jawabanDB as $key => $value) {
            if ($value->type == $type) {
                $jawabanTypeIndex = $key;
            }
        }
        foreach ($soalDB as $key => $value) {
            if ($value->type == $type) {
                $soalTypeIndex = $key;
            }
        }
        $jawaban = $jawabanDB[$jawabanTypeIndex]->jawaban;
        $soal = $soalDB[$soalTypeIndex]->soal;

        foreach ($jawaban as $key => $value) {
            foreach ($soal as $keyS => $valueS) {
                if ($value->id == $valueS->id) {
                    if ($value->jawaban == $valueS->jawaban)
                        $score += 1;
                    break;
                }
            }
        }
        $objName = 'nilai_' . $type;
        $ujian = Ujian::where('soal_id', $id)->first();
        $ujian->$objName = $score;
        $ujian->save();
        return $score;
        return $ujian;
    }
    private function selectRandomly($soal, $jumlah)
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
