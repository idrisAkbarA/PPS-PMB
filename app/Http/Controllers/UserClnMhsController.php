<?php

namespace App\Http\Controllers;

use App\UserClnMhs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserClnMhsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mahasiswa = UserClnMhs::latest()->get();

        return response()->json($mahasiswa, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\UserClnMhs  $userClnMhs
     * @return \Illuminate\Http\Response
     */
    public function show(UserClnMhs $user)
    {
        $user->getUjian();

        $reply = [
            'status' => true,
            'data' => $user
        ];
        return response()->json($reply, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UserClnMhs  $userClnMhs
     * @return \Illuminate\Http\Response
     */
    public function edit(UserClnMhs $userClnMhs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserClnMhs  $userClnMhs
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserClnMhs $userClnMhs)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserClnMhs  $userClnMhs
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserClnMhs $userClnMhs)
    {
        //
    }
}
