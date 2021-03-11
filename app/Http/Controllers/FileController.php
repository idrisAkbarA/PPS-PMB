<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
    public function download()
    {
        public_path(
            'files/' .
                $nama_periode . "/" .
                $nama_jurusan . "/" .
                $user->id . '-' . $namaUser . '/' .
                $user['nim']
        );
    );
    }
}
