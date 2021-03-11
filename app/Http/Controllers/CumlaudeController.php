<?php

namespace App\Http\Controllers;

use App\Library\Cumlaude;
use App\Periode;
use App\Jurusan;
use App\Ujian;
use Illuminate\Http\Request;

class CumlaudeController extends Controller
{
    public function index()
    {

        return response()->json((new Cumlaude())->all());
    }
    public function show($periode_id, $jurusan_id)
    {
    }
    public function initData()
    {
        $periode = Periode::select('id', 'nama')->get();
        $jurusan = Jurusan::select('id', 'nama')->get();
        return ['periode' => $periode, 'jurusan' => $jurusan];
    }
    public function update(Request $request, Ujian $ujian)
    {
        // params:  boolean is_lulus 
        //          int     id

        $result = new Cumlaude();
        $data = $result->setStatusLulus($request->is_lulus, $request->id);
        return response()->json($data, 200);
    }
}
