<?php

namespace App\Http\Controllers;

use App\KatJurusanPerPeriode;
use Illuminate\Http\Request;

class KatJurusanPerPeriodeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $kategori = KatJurusanPerPeriode::updateOrCreate(
            ['id' => optional($request)->id],
            $request->all()
        );

        $reply = [
            'status' => true,
            'data' => $kategori,
            'message' => 'Kategori Successfully Updated!',
        ];
        return response()->json($reply, 200);
    }
}
