<?php

namespace App\Http\Controllers;

use App\JadwalTR;
use App\Periode;
use Illuminate\Http\Request;

class JadwalTRController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $periode_id = $request->periode;

        $currentPeriode = Periode::getActive();
        if ($periode_id) {
            $currentPeriode = Periode::find($periode_id);
        }

        if (!is_null($currentPeriode)) {
            $temuRamah = $currentPeriode->getTemuRamah();
        }

        $reply = [
            'currentPeriode' => $currentPeriode,
            'temuRamah' => $temuRamah ?? []
        ];
        return response()->json($reply, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $reply = [
            'status' => false,
            'message' => 'Periode tidak aktif!'
        ];

        $currentPeriode = Periode::getActive();
        if ($request->periode_id == $currentPeriode->id) {
            $jadwal = JadwalTR::create($request->all());
            $reply = [
                'status' => true,
                'message' => 'Jadwal Successfully Created!',
                'data' => $jadwal
            ];
        }

        return response()->json($reply, $reply['status'] ? 201 : 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\JadwalTR  $jadwalTR
     * @return \Illuminate\Http\Response
     */
    public function show(JadwalTR $jadwalTR)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\JadwalTR  $jadwalTR
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JadwalTR $jadwal)
    {
        $jadwal->update($request->all());

        $reply = [
            'status' => true,
            'message' => 'Jadwal Successfully Created!',
            'data' => $jadwal
        ];
        return response()->json($reply, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\JadwalTR  $jadwalTR
     * @return \Illuminate\Http\Response
     */
    public function destroy(JadwalTR $jadwal)
    {
        $jadwal->delete();

        $reply = [
            'status' => true,
            'message' => 'Jadwal Successfully Deleted!'
        ];
        return response()->json($reply, 200);
    }
}
