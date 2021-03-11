<?php

namespace App\Http\Controllers;

use App\Library\Cumlaude;
use App\Periode;
use App\Ujian;
use Illuminate\Http\Request;

class CumlaudeController extends Controller
{
    public function index()
    {

        return response()->json((new Cumlaude())->all());
    }
    public function show(Request $request)
    {
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
