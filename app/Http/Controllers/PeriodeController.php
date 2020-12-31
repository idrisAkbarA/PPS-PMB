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
        $periode = Periode::create($request->all());

        $reply = [
            'status' => true,
            'message' => 'Periode Successfully Created!',
            'data' => $periode
        ];
        return response()->json($reply, 201);
    }

    /**
     * Display the current active periode.
     *
     * @param  \App\Periode  $periode
     * @return \Illuminate\Http\Response
     */
    public function show(Periode $periode)
    {
        $periode = Periode::getActive();

        return response()->json($periode, 200);
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
