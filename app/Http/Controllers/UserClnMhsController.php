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
    public function store(Request $request)
    {
        $form = $this->validate($request, [
            'nama' => 'required|string',
            'email' => 'required|string|email|unique:user_cln_mhs,email',
            'password' => 'required|string|same:password2',
            'password2' => 'required|string',
        ]);

        $form['password'] = Hash::make($request->password);
        $user = UserClnMhs::create($form);

        Auth::guard('cln_mahasiswa')->login($user);

        return redirect('/user/cln-mhs/home');
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\UserClnMhs  $userClnMhs
     * @return \Illuminate\Http\Response
     */
    public function show(UserClnMhs $user)
    {
        $data = [
            'user' => $user
        ];

        return response()->json($data);
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
