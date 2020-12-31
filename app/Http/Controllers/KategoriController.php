<?php

namespace App\Http\Controllers;

use App\Jurusan;
use App\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kategori = Kategori::getAll();

        return response()->json($kategori, 200);
    }

    /**
     * Store newly created resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Kategori::create($request->all());

        $reply = [
            'status' => true,
            'message' => 'Kategori Successfully Updated!',
        ];
        return response()->json($reply, 200);
    }

    /**
     * Store categories in jurusan menu.
     *
     * @param  \App\Jurusan  $jurusan
     * @return \Illuminate\Http\Response
     */
    public function storeInJurusan(Request $request, Jurusan $jurusan)
    {
        $categories = $request->categories;

        Kategori::setKategoriInJurusan($jurusan->id, $categories);

        $reply = [
            'status' => true,
            'message' => 'Kategori Successfully Created!',
        ];
        return response()->json($reply, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kategori $kategori)
    {
        $kategori->update($request->all());

        $reply = [
            'status' => true,
            'message' => 'Kategori Successfully Updated!',
        ];
        return response()->json($reply, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kategori $kategori)
    {
        $kategori->delete();

        $reply = [
            'status' => true,
            'message' => 'Kategori Successfully Deleted!',
        ];
        return response()->json($reply, 200);
    }
}
