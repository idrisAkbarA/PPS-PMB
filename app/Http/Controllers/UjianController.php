<?php

namespace App\Http\Controllers;

use App\Ujian;
use App\Periode;
use App\Jurusan;
use App\Library\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UjianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function initAllDataClnMhs()
    {
        $user = Auth::guard('cln_mahasiswa')->user();
        $periode = Periode::latest()->get();
        $ujian_temp = Ujian::where(['user_cln_mhs_id' => $user->id])->with(['jurusan', 'periode'])->get();
        $jurusan = Jurusan::all();
        $ujian = count($ujian_temp) > 0 ? $ujian_temp : null;
        return response()->json(['user' => $user, 'periode' => $periode, 'jurusan' => $jurusan, 'ujian' => $ujian], 200);
    }

    public function index(Request $request)
    {
        $periode_id = $request->periode;
        $jurusan_id = $request->jurusan;
        $pembayaran = $request->pembayaran;
        $status = $request->status;

        $currentPeriode = Periode::getActive();
        if ($periode_id) {
            $currentPeriode = Periode::find($periode_id);
        }

        if (!is_null($currentPeriode)) {
            $pendaftaran = $currentPeriode->getUjian($jurusan_id, $pembayaran, $status);
        }

        $reply = [
            'currentPeriode' => $currentPeriode,
            'pendaftaran' => $pendaftaran ?? []
        ];
        return response()->json($reply, 200);
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
    public function initUjian(Request $request)
    {
        $user = Auth::guard("cln_mahasiswa")->user();
        $periode_id = $request->periode_id;
        $jurusan_id = $request->jurusan_id;
        $ujian_id = $request->ujian_id ?? null;
        if (!$ujian_id) {
            $ujian = Ujian::create(
                [
                    "user_cln_mhs_id" => $user->id,
                    "periode_id" => $periode_id,
                    "jurusan_id" => $jurusan_id
                ]
            );
            return response()->json([
                "status" => true,
                "message" => "Ujian Succesfully intialized",
                "ujian_id" => $ujian->id
            ]);
        }
        $ujian = ujian::find($ujian_id);
        $ujian->jurusan_id = $jurusan_id;
        $ujian->save();

        return response()->json([
            "status" => true,
            "message" => "Ujian Succesfully updated",
            "ujian_id" => $ujian->id
        ]);
    }
    public function generatePembayaran(Request $request)
    {
        $ujian_id = $request->ujian_id;
        $pembayaran = new Pembayaran;
        $code = $pembayaran->generate($ujian_id);

        $ujian = Ujian::find($ujian_id);
        $ujian->kode_bayar = $code;
        $ujian->save();

        return response()->json(['status' => true, 'message' => 'Kode bayar berhasil dibuat', 'code' => $code]);
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
     * @param  \App\Ujian  $ujian
     * @return \Illuminate\Http\Response
     */
    public function show(Ujian $ujian)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Ujian  $ujian
     * @return \Illuminate\Http\Response
     */
    public function edit(Ujian $ujian)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ujian  $ujian
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ujian $ujian)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ujian  $ujian
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ujian $ujian)
    {
        //
    }
}
