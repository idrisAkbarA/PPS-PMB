<?php

namespace App\Http\Controllers;

use App\Kelas;
use App\KuotaKelasPerPeriode;
use App\UserClnMhs;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
    public function store($periode_id, $jurusan_id, $clnMhs_id)
    {
        $clnMhsSelected = UserClnMhs::find($clnMhs_id);
        $finalClnMhs = [['id' => $clnMhsSelected->id, 'nama' => $clnMhsSelected->nama]];
        Kelas::create([
            'periode_id' => $periode_id,
            'jurusan_id' => $jurusan_id,
            'cln_mhs' => $finalClnMhs,
        ]);
    }
    public function addNewStudent($periode_id, $jurusan_id, $clnMhs_id)
    {
        $kuota = KuotaKelasPerPeriode::where(['periode_id' => $periode_id, 'jurusan_id' => $jurusan_id])->first();
        $kelas = Kelas::where(['periode_id' => $periode_id, 'jurusan_id' => $jurusan_id])->latest('id')->first();
        // dd($kelas);

        if (!$kelas) {
            echo 'belum ada kelas';
            $this->store($periode_id, $jurusan_id, $clnMhs_id);
            return 0;
        }
        if (count($kelas->cln_mhs) >= $kuota->kuota) {
            echo 'Kelas terakhir penuh';
            $this->store($periode_id, $jurusan_id, $clnMhs_id);
            return 0;
        }
        echo 'new student appended to latest class available';
        $this->appendStudent($periode_id, $jurusan_id, $clnMhs_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Kelas  $kelas
     * @return \Illuminate\Http\Response
     */
    public function show(Kelas $kelas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Kelas  $kelas
     * @return \Illuminate\Http\Response
     */
    public function edit(Kelas $kelas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Kelas  $kelas
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kelas $kelas)
    {
        //
    }
    public function appendStudent($periode_id, $jurusan_id, $clnMhs_id)
    {
        // get the student
        $clnMhsSelected = UserClnMhs::find($clnMhs_id);
        $finalClnMhs = ['id' => $clnMhsSelected->id, 'nama' => $clnMhsSelected->nama];

        //get latest record of kelas
        $kelas = Kelas::where(['periode_id' => $periode_id, 'jurusan_id' => $jurusan_id])->latest('id')->first();
        $clnMhsInKelas = $kelas->cln_mhs;

        //push new student to kelas
        array_push($clnMhsInKelas, $finalClnMhs);
        $kelas->cln_mhs = $clnMhsInKelas;
        $kelas->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Kelas  $kelas
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kelas $kelas)
    {
        //
    }
}
