<?php

namespace App\Http\Controllers;

use App\Soal;
use App\Ujian;
use App\Periode;
use App\Library\SoalUjian;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SoalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function get($ujian_id, $type, $soal_id)
    {
        //if soal_id is not exist then generate soal
        $user = Auth::guard('cln_mahasiswa')->user();
        if ($soal_id=="null") {
            // dd($soal_id);
            $soal_id = $this->generate($ujian_id);
        }
        $soal = Soal::find($soal_id);
        $ujian = Ujian::find($ujian_id);
        $isUserValid = $user->id == $ujian->user_cln_mhs_id;

        // get periode instance to get the soal duration later
        $periode = Periode::find($ujian->periode_id);

        // set start soal time if not exist
        $start_soal = 'start_'.$type;
        if(!$ujian->$start_soal){
            $ujian->$start_soal = Carbon::now();
            $ujian->save();
        }

        // check if the user id from request is
        // the real owner of the soal
        if (!$isUserValid) {
            return response()->json(["status" => false, "message" => "User id is not belong to the soal id"]);
        }

        $ujian = Ujian::find($ujian_id);
        // finally get the soal
        $soalUjian = new SoalUjian;
        $result = $soalUjian->get($type, $soal_id);

        // append other atribute
        $result['id'] = $soal_id;
        $result['start_time'] = $ujian->$start_soal;
        $result['durasi'] = $periode->durasi_ujian;
        return response()->json($result);
    }
    public function generate($ujian_id)
    {
        $ujian = Ujian::find($ujian_id);
        // $jurusan_id = $ujian->jurusan_id;
        // $tka_id = $ujian->kat_tka_id;
        // $tkj_id = $ujian->kat_tkj_id;
        $jurusan_id = $ujian['jurusan_id'];
        $tka_id = $ujian['kat_tka_id'];
        $tkj_id = $ujian['kat_tkj_id'];
        $jum_tka = Periode::find($ujian['periode_id'])['jumlah_tka'];
        $jum_tkd = Periode::find($ujian['periode_id'])['jumlah_tkj'];
        $soalUjian = new SoalUjian;
        $soalUjian->generate(
            $jurusan_id,
            $tka_id,
            $tkj_id,
            $jum_tka,
            $jum_tkd,
            $ujian_id
        );
        return $soalUjian->id;
    }
    public function setJawaban(Request $request)
    {
        $type = $request->type;
        $rowID = $request->rowID;
        $soalID = $request->soalID;
        $jawaban = $request->jawaban;
        $soal = new SoalUjian;
        return response()->json($soal->setJawaban($type, $rowID, $soalID, $jawaban));
    }
    public function calcScore(Request $request)
    {
        $type = $request->type;
        $id = $request->id;

        $soal = new SoalUjian;
        $soal->calcScore($id, $type);
        $setLulus = $this->setLulus($request);

        $ujian = Ujian::find($request->idUjian);
        if($ujian['is_lulus_tka']==true && $ujian['is_lulus_tkj']==true){
            $ujian->lulus_at = Carbon::now();
            $ujian->save();
        }
        return response()->json($setLulus);
    }
    public function setLulus(Request $request)
    {
        $soalUjian = new SoalUjian;
        $idUjian = $request->idUjian;
        $type = $request->type;

        return $soalUjian->setLulus($idUjian, $type);
    }
    public function test()
    {
        $jawabans = ['A', 'B', 'C', 'D', 'E'];
        $soal = Soal::all();
        // return $soal;
        foreach ($soal as $key => $value) {
            $idSoal = $value->id;
            foreach ($value->set_pertanyaan[0]->soal as $keyS => $valueS) {
                $idPertanyaan = $valueS->id;
                $soalLib = new SoalUjian;
                $jawaban = $jawabans[rand(0, count($jawabans) - 1)];
                $soalLib->setJawaban('tka', $idSoal, $idPertanyaan, $jawaban);
            }
            foreach ($value->set_pertanyaan[1]->soal as $keyS => $valueS) {
                $idPertanyaan = $valueS->id;
                $soalLib = new SoalUjian;
                $jawaban = $jawabans[rand(0, count($jawabans) - 1)];
                $soalLib->setJawaban('tkj', $idSoal, $idPertanyaan, $jawaban);
            }
        }
        echo "Jawaban Stored";
    }
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Soal  $soal
     * @return \Illuminate\Http\Response
     */
    public function show(Soal $soal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Soal  $soal
     * @return \Illuminate\Http\Response
     */
    public function edit(Soal $soal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Soal  $soal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Soal $soal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Soal  $soal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Soal $soal)
    {
        //
    }
}
