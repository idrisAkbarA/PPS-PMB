<?php

namespace App\Http\Controllers;

use App\UserPetugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPetugasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $petugas = UserPetugas::latest()->get();

        return response()->json($petugas, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $petugas = UserPetugas::create($request->all());

        $reply = [
            'status' => true,
            'message' => 'Petugas Successfully Created!',
            'data' => $petugas
        ];
        return response()->json($reply, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\UserPetugas  $userPetugas
     * @return \Illuminate\Http\Response
     */
    public function show(UserPetugas $userPetugas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserPetugas  $petugas
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserPetugas $petugas)
    {
        $petugas->update([
            'password' => $request->password
        ]);

        $reply = [
            'status' => true,
            'message' => 'Petugas Successfully Updated!',
        ];
        return response()->json($reply, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserPetugas  $petugas
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserPetugas $petugas)
    {
        $user = Auth::guard('petugas')->user();

        $reply = [
            'status' => false,
            'message' => 'Opps something wrong!',
        ];

        if ($petugas != $user) {
            $petugas->delete();
            $reply = [
                'status' => true,
                'message' => 'Petugas Successfully Deleted!',
            ];
        }
        return response()->json($reply, 200);
    }
}
