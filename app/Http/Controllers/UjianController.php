<?php

namespace App\Http\Controllers;

use App\Ujian;
use App\Periode;
use App\Jurusan;
use App\KatJurusanPerPeriode;
use App\Library\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Library\SoalUjian;
use Illuminate\Support\Facades\Auth;

class UjianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $periode = Periode::latest()->first();
        $periode_id = $periode->id;
        $ujian = Ujian::where(['periode_id' => $periode_id])->get();
        $total_pendaftaran = count($ujian);
        $total_lulus = count(Ujian::where(['periode_id' => $periode_id])->where("lulus_at", "!=", null)->get());
        return response()->json(
            [
                "periode" => $periode,
                "total_pendaftaran" => $total_pendaftaran,
                "total_lulus" => $total_lulus
            ]
        );
    }
    public function initAllDataClnMhs()
    {
        $user = Auth::guard('cln_mahasiswa')->user();
        $periode = Periode::latest()->get();
        $active_periode = Periode::getActive();
        $ujian_temp = Ujian::where(['user_cln_mhs_id' => $user->id])->orderBy('id', 'DESC')->with(['jurusan', 'periode'])->get();
        $jurusan = Jurusan::orderBy('id', 'DESC')->get();
        $ujian = count($ujian_temp) > 0 ? $ujian_temp : null;
        return response()->json([
            'active_periode' => $active_periode,
            'user' => $user,
            'periode' => $periode,
            'jurusan' => $jurusan,
            'ujian' => $ujian
        ], 200);
    }

    public function laporan()
    {
        $periode = periode::orderBy('id', 'DESC')->with("ujian")->get();
        return response()->json($periode);
    }

    public function getPendaftaran(Request $request)
    {
        $user = Auth::guard('cln_mahasiswa')->user();
        $jurusan = Jurusan::all();
        $jurusan_id = $request->jurusan_id;
        $ujian = Ujian::find($jurusan_id);
        return response()->json([
            "user" => $user,
            "jurusan" => $jurusan,
            "ujian" => $ujian

        ]);
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
        // this function is the called from tambah pendaftaran baru
        // to init or store the ujian for the first time
        $user = Auth::guard("cln_mahasiswa")->user();
        $periode_id = $request->periode_id;

        $jurusan_id = $request->jurusan_id;
        $kategori = KatJurusanPerPeriode::where([
            'periode_id' => $periode_id,
            'jurusan_id' => $jurusan_id
        ])->first();
        $ujian_id = $request->ujian_id ?? null;
        if (!$ujian_id) {
            $ujian = Ujian::create(
                [
                    "user_cln_mhs_id" => $user->id,
                    "periode_id" => $periode_id,
                    "jurusan_id" => $jurusan_id,
                    'komposisi_tka' => $kategori->komposisi_tka,
                    'komposisi_tkj' => $kategori->komposisi_tkj
                ]
            );
            return response()->json([
                "status" => true,
                "message" => "Ujian Succesfully intialized",
                "ujian_id" => $ujian->id,
                "ujian_selected" => $ujian
            ]);
        }
        $ujian = ujian::find($ujian_id);
        $ujian->jurusan_id = $jurusan_id;
        $ujian->komposisi_tka = $kategori->komposisi_tka;
        $ujian->komposisi_tkj = $kategori->komposisi_tkj;
        $ujian->save();

        return response()->json([
            "status" => true,
            "message" => "Ujian Succesfully updated",
            "ujian_id" => $ujian->id,
            "ujian_selected" => $ujian
        ]);
    }
    public function generatePembayaran(Request $request)
    {
        $ujian_id = $request->ujian_id;
        $pembayaran = new Pembayaran;
        $code = $pembayaran->generate($ujian_id);

        //save kode bayar
        $ujian = Ujian::find($ujian_id);
        $ujian->kode_bayar = $code;
        $ujian->save();

        return response()->json(['status' => true, 'message' => 'Kode bayar berhasil dibuat', 'code' => $code]);
    }
    public function pay(Request $request)
    {
        // this function set pembayaran to be 'lunas' by setting the lunas_at date.
        // and also, set the deadline of the corresponding ujian

        // try {
        //code...
        $kode_bayar = $request->kode_bayar;
        $soalUjian = new SoalUjian;
        $ujian = Ujian::where(['kode_bayar' => $kode_bayar])->first();
        $batas_ujian = $soalUjian->calcDeadline($ujian->id);
        $ujian->lunas_at = Carbon::now();
        $ujian->batas_ujian = $batas_ujian;
        $ujian->save();
        return response()->json([
            'status' => true,
            "message" => 'Pembayaran berhasil',
        ]);
        // } catch (\Throwable $th) {
        //     //throw $th;
        //     return response()->json([
        //         'status' => false,
        //         "message" => 'something went wrong',
        //         'error' => $th
        //     ]);
        // }
    }
    public function checkPembayaran(Request $request)
    {
        $ujian_id = $request->ujian_id;
        $ujian = Ujian::find($ujian_id);
        if ($ujian->lunas_at) {
            return response()->json([
                'status' => true,
                "message" => 'Pembayaran sudah lunas',
                "ujian" => $ujian
            ]);
        } else {
            return response()->json([
                'status' => false,
                "message" => 'Pembayaran belum lunas',
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function test(Request $request)
    {
        $soalUjian = new SoalUjian;
        // return $soalUjian->setDeadline($request->id);
    }
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
        $ujian->update($request->all());
        // $ujian->save();
        return response()->json(['status' => "Update Succesfull", 'request' => $request->all()]);
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
