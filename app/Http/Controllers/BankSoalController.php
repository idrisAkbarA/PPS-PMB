<?php

namespace App\Http\Controllers;

use App\BankSoal;
use Illuminate\Http\Request;

class BankSoalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $jurusan_id = $request->jurusan;
        $kategori_id = $request->kategori;

        $soal = BankSoal::getByKategori($kategori_id, $jurusan_id);

        return response()->json($soal, 200);
    }

    public function getSoalTKA(Request $request)
    {
        $jurusan_id = $request->jurusan;
        $kategori_id = $request->kategori;

        $soal = BankSoal::getTkaByKategori($kategori_id, $jurusan_id);

        return response()->json($soal, 200);
    }

    public function getSoalTKJ(Request $request)
    {
        $jurusan_id = $request->jurusan;
        $kategori_id = $request->kategori;

        $soal = BankSoal::getTkjByKategori($kategori_id, $jurusan_id);

        return response()->json($soal, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $soal = BankSoal::create($request->all());

        $reply = [
            'status' => true,
            'message' => 'Soal Successfully Created!',
            'data' => $soal
        ];
        return response()->json($reply, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BankSoal  $soal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BankSoal $soal)
    {
        $soal->update($request->all());

        $reply = [
            'status' => true,
            'message' => 'Soal Successfully Updated!',
            'data' => $soal
        ];
        return response()->json($reply, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BankSoal  $soal
     * @return \Illuminate\Http\Response
     */
    public function destroy(BankSoal $soal)
    {
        $soal->delete();

        $reply = [
            'status' => true,
            'message' => 'Soal Successfully Deleted!',
        ];
        return response()->json($reply, 200);
    }
}
