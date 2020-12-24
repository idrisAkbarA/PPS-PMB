<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\UserClnMhs;
use App\UserPetugas;

class AuthController extends Controller
{
    public function login(Request $request, $role)
    {
        $role == "cln_mahasiswa" ?
            $loginIdentity = "email"
            : $loginIdentity = "username";

        $credentials = $request->only($loginIdentity, 'password');

        if (Auth::guard($role)->attempt($credentials)) {
            // Authentication passed...
            return response()->json([
                'status' => 'Authenticated',
                'user' => Auth::guard($role)->user()
            ]);
        }
        return response()->json([
            'status' => 'Not Authenticated',
        ]);
    }

    public function loginServer(Request $request, $role)
    {
        $result = UserClnMhs::all();
        return response()->json($result);
        if ($role == "cln_mahasiswa") {
            $loginIdentity = "email";
            $credentials = $request->only($loginIdentity, 'password');
            $user = new UserClnMhs;
        } else {
            $loginIdentity = "username";
            $credentials = $request->only($loginIdentity, 'password');
            $user = new UserPetugas;
        }
        $user->where($loginIdentity, $credentials[$loginIdentity])->first();
        $result = UserClnMhs::all();
        return response()->json($result);
        return [$loginIdentity, $credentials[$loginIdentity]];
    }

    public function isLogin(Request $request, $role)
    {
        if (Auth::guard($role)->check()) {
            // user logged in...
            return response()->json([
                'status' => 'Authenticated'
            ]);
        }
        return response()->json([
            'status' => 'Not Authenticated',
        ]);
    }

    public function register(Request $request)
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
    }

    public function logout(Request $request)
    {
        return Auth::guard('cln_mahasiswa')->logout();
    }

    public function logoutPetugas(Request $request)
    {
        Auth::guard('petugas')->logout();
    }
}
