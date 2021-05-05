<?php

namespace App\Http\Controllers;

use App\Ujian;
use App\Periode;
use App\Jurusan;
use App\Kelas;
use App\KatJurusanPerPeriode;
use App\Library\Pembayaran;
use App\Library\ReportUjian;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Library\SoalUjian;
use Faker\Provider\ar_SA\Payment;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\pendaftaranExport;
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
        // $periode = Periode::latest()->first();
        $periode = Periode::getActive();
        $periode_id = $periode->id;
        $ujian = Ujian::where(['periode_id' => $periode_id])->get();
        $total_pendaftaran = count($ujian);
        $total_lulus = count(Ujian::where(['periode_id' => $periode_id])->where("lulus_at", "!=", null)->get());
        $jurusan = Jurusan::all();
        $total_gagal = 0;
        $final_data = [];
        foreach ($jurusan as $key => $value) {
            $dataJurusan = Ujian::where(['jurusan_id' => $value->id, 'periode_id' => $periode_id])->get();
            $jumlah_pendaftar = count($dataJurusan);
            $jumlah_lulus =  count(Ujian::where(['jurusan_id' => $value->id, 'periode_id' => $periode_id])->whereNotNull('lulus_at')->get());
            $jumlah_kelas_terisi = count(Kelas::where(['jurusan_id' => $value->id, 'periode_id' => $periode_id])->get());
            $jumlah_gagal = (new ReportUjian())->totalGagal($dataJurusan);
            $total_gagal += $jumlah_gagal;
            $jumlah_belum_ujian = $jumlah_pendaftar - ($jumlah_lulus + $jumlah_gagal);
            $array_temp =  [
                'nama_jurusan' => $value->nama,
                'jumlah_pendaftar' => $jumlah_pendaftar,
                'jumlah_lulus' => $jumlah_lulus,
                'jumlah_kelas_terisi' => $jumlah_kelas_terisi,
                'jumlah_gagal' => $jumlah_gagal,
                'jumlah_belum_ujian' => $jumlah_belum_ujian
            ];
            array_push($final_data, $array_temp);
        }
        return response()->json(
            [
                "periode" => $periode,
                "total_pendaftaran" => $total_pendaftaran,
                "total_lulus" => $total_lulus,
                "total_gagal" => $total_gagal,
                "final_data" => $final_data,
            ]
        );
    }
    public function initAllDataClnMhs()
    {
        $user = Auth::guard('cln_mahasiswa')->user();
        $periode = Periode::latest()->get();
        $active_periode = Periode::getActive();
        $ujian_temp = Ujian::where(['user_cln_mhs_id' => $user->id])->with('periode')->orderBy('id', 'DESC')->with(['jurusan', 'periode'])->get();
        $jurusan = Jurusan::orderBy('id')->get();
        $ujian = count($ujian_temp) > 0 ? $ujian_temp : null;
        return response()->json([
            'active_periode' => $active_periode,
            'user' => $user,
            'periode' => $periode,
            'jurusan' => $jurusan,
            'ujian' => $ujian
        ], 200);
    }

    public function laporan(Request $request)
    {
        $name = $request->name;
        $periode = periode::orderBy('id', 'DESC')->with("ujian")->get();
        $count = 0;
        $result = $periode;
        if ($name) {
            $isName = true;
            $result = $periode->toArray();
            foreach ($result as $key => $value) {
                $temp = [];
                foreach ($value['ujian'] as $k => $ujian) {
                    // echo $ujian['nama_pendaftar'];
                    $isFound = stripos($ujian['nama_pendaftar'], $name);
                    if (gettype($isFound) == 'integer') {
                        $count++;
                        array_push($temp, $ujian);
                    }
                }
                $result[$key]['ujian'] = $temp;
                $result[$key]['record_found'] = $count;
            }
        }
        // return response()->json($name);
        // return $name;
        // return gettype($periode[0]['ujian']);
        // return (array)$periode[0]['ujian'] = ['pantek'];
        return response()->json($result);
        return response()->json([$result, $isSearched, $isName]);
    }
    public function export(Request $request)
    {
        $periode_id = $request->periode;
        $jurusan_id = $request->jurusan;
        $pembayaran = $request->pembayaran;
        $status = $request->status;
        return Excel::download(new pendaftaranExport($jurusan_id, $periode_id, $pembayaran, $status), 'template.xlsx');
    }
    public function getPendaftaran(Request $request)
    {
        $user = Auth::guard('cln_mahasiswa')->user();
        $jurusan = Jurusan::all();
        $jurusan_id = $request->jurusan_id;
        $ujian = Ujian::with('periode')->find($jurusan_id);
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
            $kelas = $currentPeriode->getKelas($jurusan_id);
        }

        $reply = [
            'currentPeriode' => $currentPeriode,
            'pendaftaran' => $pendaftaran ?? [],
            'kelas' => $kelas ?? []
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
            $ujian = ujian::with('periode')->find($ujian->id);
            return response()->json([
                "status" => true,
                "message" => "Ujian Succesfully intialized",
                "ujian_id" => $ujian->id,
                "ujian_selected" => $ujian
            ]);
        }
        $ujian = ujian::with('periode')->find($ujian_id);
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
    public function resetPayment($id_ujian)
    {
        $payment = (new Pembayaran())->reset($id_ujian);
        return response()->json(['status' => true, 'message' => 'Pembayaran berhasil di reset']);
    }
    public function resetUjian($id_ujian)
    {
        $soalUjian = (new SoalUjian())->reset($id_ujian);
        return response()->json(['status' => true, 'message' => 'Ujian berhasil di reset']);
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
        if ($ujian->is_jalur_cumlaude == 1) {
            $ujian->lulus_at = Carbon::now();
        }
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
        $ujian = Ujian::with('periode')->find($ujian_id);
        if ($ujian->lunas_at) {
            $soalUjian = new SoalUjian;
            $batas_ujian = $soalUjian->calcDeadline($ujian->id, $ujian->lunas_at);
            $ujian->batas_ujian = $batas_ujian;
            $ujian->save();
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
