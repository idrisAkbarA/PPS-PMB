<?php

namespace App\Http\Controllers;

use App\Soal;
use App\Library\SoalUjian;
use Illuminate\Http\Request;

class SoalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get($id, $type)
    {
        $soal = new SoalUjian;
        return response()->json($soal->get($type, $id));
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
        return response()->json($soal->calcScore($id, $type));
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
