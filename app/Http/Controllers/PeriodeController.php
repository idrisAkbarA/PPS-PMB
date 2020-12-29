<?php

namespace App\Http\Controllers;

use App\Periode;
use Illuminate\Http\Request;

class PeriodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $periode = Periode::latest()->get();

        return response()->json($periode, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $this->validate($request, [
        //     'nama' => 'required|string',
        //     'awal_periode' => 'required|date',
        //     'akhir_periode' => 'required|date',
        //     'range_ujian' => 'required|digits',
        //     'syarat_ipk' => '',
        //     'syarat_bhs' => '',
        //     'awal_temu_ramah' => 'required|date',
        //     'akhir_temu_ramah' => 'required|date',
        // ]);
        $periode = Periode::create($request->all());

        $reply = [
            'status' => true,
            'message' => 'Periode Successfully Created!',
            'data' => $periode
        ];
        return response()->json($reply, 201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Periode  $periode
     * @return \Illuminate\Http\Response
     */
    public function edit(Periode $periode)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Periode  $periode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Periode $periode)
    {
        $periode->update($request->all());

        $reply = [
            'status' => true,
            'message' => 'Periode Successfully Updated!',
            'data' => $periode
        ];
        return response()->json($reply, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Periode  $periode
     * @return \Illuminate\Http\Response
     */
    public function destroy(Periode $periode)
    {
        $periode->delete();

        $reply = [
            'status' => true,
            'message' => 'Periode Successfully Deleted!',
        ];
        return response()->json($reply, 200);
    }
}
