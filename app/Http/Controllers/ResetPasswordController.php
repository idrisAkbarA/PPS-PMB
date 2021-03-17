<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\UserClnMhs; 
use Hash;

class ResetPasswordController extends Controller
{
    public function updatePassword(Request $request)
  {
    // return $request;
  $request->validate([
      'email' => 'required|email|exists:user_cln_mhs',
      'password' => 'required|string|min:6|confirmed',
      'password_confirmation' => 'required',

  ]);

  $updatePassword = DB::table('password_resets')
                      ->where(['email' => $request->email, 'token' => $request->token])
                      ->first();

  if(!$updatePassword){
    // return back()->withInput()->with('error', 'Invalid token!');
    return response()->json([
      'status'=> false,
      'error'=> 'Invalid token!'
      ]);
  }

    $user = UserClnMhs::where('email', $request->email)
                ->update(['password' => Hash::make($request->password)]);

    DB::table('password_resets')->where(['email'=> $request->email])->delete();

    return response()->json([
      'status'=> true,
      'message'=> 'Password berhasil diubah!'
      ]);

  }
}
