<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
class AuthController extends Controller
{
        public function login(Request $request){
            $credentials = $request->only('email', 'password');
            if (Auth::guard("cln_mahasiswa")->attempt($credentials)) {
                // Authentication passed...
                return response()->json([
                    'status' => 'Authenticated'
                ]);
            }
            return response()->json([
                'status' => 'Not Authenticated',
            ]);
        }
}
