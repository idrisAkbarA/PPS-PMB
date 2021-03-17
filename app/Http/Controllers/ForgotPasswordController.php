<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DB; 
use Carbon\Carbon; 
use Mail;

class ForgotPasswordController extends Controller
{
    public function postEmail(Request $request){
      // return $request;
    $request->validate([
        'email' => 'required|email|exists:user_cln_mhs',
    ]);

    $token = Str::random(64);

      DB::table('password_resets')->insert(
          ['email' => $request->email, 'token' => $token, 'created_at' => Carbon::now()]
      );

      Mail::send('verifyEmail', ['token' => $token], function($message) use($request){
          $message->from('bookingkelasdia@gmail.com');
          $message->to($request->email);
          $message->subject('Reset Password Notification');
      });

      return response()->json([
        'status'=> true,
        // 'message'=> 'Link reset password berhasil terkirim. Cek emailmu sekarang!'
        ]);
  }
}
