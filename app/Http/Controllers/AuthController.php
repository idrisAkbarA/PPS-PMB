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
                'status' => 'Authenticated',
                'value' => true
            ]);
        }
        return response()->json([
            'status' => 'Not Authenticated',
            'value' => false
        ]);
    }
    public function changePassword(Request $request)
    {
        // check if it is mahasiswa or petugas
        // mahasiswa is using email as login identity
        // petugas is using username
        $user = null;
        if (filter_var($request->username, FILTER_VALIDATE_EMAIL)) {
            $user = UserClnMhs::where('email', $request->username)->first();
        } else {
            $user = UserPetugas::where('username', $request->username)->first();
        }

        //check if user exist
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'User not found.']);
        }

        // validate the old password
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['status' => false, 'message' => $user->nama . "'s" . ' old password not matched.']);
        }

        $user->password = $request->new_password;
        $user->save();

        return response()->json(['status' => true, 'message' => 'Password changed succesfully.']);
    }
    public function register(Request $request)
    {
        $form = $this->validate($request, [
            'nama' => 'required|string',
            'email' => 'required|string|email|unique:user_cln_mhs,email',
            'password' => 'required|string|same:password2',
            'password2' => 'required|string',
        ]);

        $user = UserClnMhs::create($form);

        Auth::guard('cln_mahasiswa')->login($user);

        return response()->json([
            'status' => true,
            'data' => $user
        ]);
    }
    public function user($role)
    {
        return response()->json(Auth::guard($role)->user());
    }

    public function logout(Request $request)
    {
        return Auth::guard('cln_mahasiswa')->logout();
    }

    public function logoutPetugas(Request $request)
    {
        Auth::guard('petugas')->logout();
        return Auth::guard($role)->check();
    }
}
