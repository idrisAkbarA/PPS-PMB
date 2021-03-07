<?php

namespace App\Http\Controllers;

use App\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeriodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $periode = Periode::with('kategori')->latest()->get();

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
        DB::beginTransaction();

        try {
            // Create periode
            $periode = Periode::create($request->all());
            // Create kategori per periode
            $periode->setKategori($request->jurusan);

            DB::commit();

            $reply = [
                'status' => true,
                'message' => 'Periode Successfully Created!',
                'data' => $periode
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $reply = [
                'status' => false,
                'message' => 'Opps something went wrong!',
                'exception' => $e
            ];
        }

        return response()->json($reply, $reply['status'] ? 201 : 409);
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
        DB::beginTransaction();

        try {
            // Create periode
            $periode->update($request->all());
            // Create kategori per periode
            $periode->setKategori($request->jurusan);

            DB::commit();

            $reply = [
                'status' => true,
                'message' => 'Periode Successfully Updated!',
                'data' => $periode
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $reply = [
                'status' => false,
                'message' => 'Opps something went wrong!',
                'exception' => $e
            ];
        }

        return response()->json($reply, $reply['status'] ? 200 : 409);
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
