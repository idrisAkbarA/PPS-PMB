<?php

namespace App\Http\Controllers;

use App\Jurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jurusan = Jurusan::getAll();

        return response()->json($jurusan, 200);
    }

    public function getAll()
    {
        return response()->json(Jurusan::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $jurusan = Jurusan::create($request->all());

        $reply = [
            'status' => true,
            'message' => 'Jurusan Successfully Created!',
            'data' => $jurusan
        ];
        return response()->json($reply, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Jurusan  $jurusan
     * @return \Illuminate\Http\Response
     */
    public function show(Jurusan $jurusan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Jurusan  $jurusan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Jurusan $jurusan)
    {
        $jurusan->update($request->all());

        $reply = [
            'status' => true,
            'message' => 'Jurusan Successfully Updated!',
            'data' => $jurusan
        ];
        return response()->json($reply, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Jurusan  $jurusan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Jurusan $jurusan)
    {
        $jurusan->delete();

        $reply = [
            'status' => true,
            'message' => 'Jurusan Successfully Deleted!',
        ];
        return response()->json($reply, 200);
    }
}
