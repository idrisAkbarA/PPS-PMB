<?php

namespace App\Http\Controllers;

use App\Periode;
use App\Jurusan;
use App\UserClnMhs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

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

    public function storeFile(Request $request)
    {
        $user = Auth::guard("cln_mahasiswa")->user();
        $user_id = $user->id;
        $periode = Periode::find($request->periode_id);
        $jurusan = Jurusan::find($request->jurusan_id);
        $nama_periode = str_replace("/", "-", $periode->nama);
        $nama_jurusan = str_replace(" ", "-", $jurusan->nama);
        $nama_file = str_replace(" ", "-", $request->file->getClientOriginalName());
        $namaUser = str_replace(" ", "-", $user['nama']);
        $fileName =
            Carbon::now()->format("Y-m-d-H-i-s") .
            $nama_file;
        $request->file->move(
            public_path(
                'files/' .
                    $nama_periode . "/" .
                    $nama_jurusan . "/" .
                    $user->id . '-' . $namaUser . '/' .
                    $user['nim']
            ),
            $fileName
        );

        // call to a spesific method requested by user dynamically, such as savePhotoPath
        if ($request->methodName != null) {
            $method = $request->methodName;
            $this->$method($fileName);
        }
        $user = UserClnMhs::find($user_id);
        return response()->json(['success' => 'You have successfully upload file.', 'file_name' => $fileName, 'user' => $user]);
    }
    private function savePhotoPath($path)
    {
        //this function save pas photo path
        $user_id = Auth::guard("cln_mahasiswa")->user()->id;
        $userInstance = UserClnMhs::find($user_id);
        $userInstance->pas_photo = $path;
        $userInstance->save();
    }
    private function saveIjazahPath($path)
    {
        //this function save pas photo path
        $user_id = Auth::guard("cln_mahasiswa")->user()->id;
        $userInstance = UserClnMhs::find($user_id);
        $userInstance->ijazah = $path;
        $userInstance->save();
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
    public function update(Request $request, UserClnMhs $user)
    {
        $user->update($request->all());
        $reply = [
            'message' => 'Berhasil mengubah',
            'status' => true,
            'data' => $user
        ];
        return response()->json($reply, 200);
    }

    public function selfUpdate(Request $request)
    {
        $user = Auth::guard("cln_mahasiswa")->user();
        if (is_null($user)) {
            return;
        }
        return $this->update($request, $user);
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
