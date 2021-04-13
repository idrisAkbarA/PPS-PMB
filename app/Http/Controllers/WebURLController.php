<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class WebURLController extends Controller
{
    public function spa()
    {
        return view("layouts.app");
    }
    public function spaPetugas()
    {
        return view("layouts.app-petugas");
    }
    public function landingPage()
    {
        $is_landing_page = true;
        return view("landingPage", ['is_landing_page' => $is_landing_page]);
    }
    public function pendaftaran()
    {
        $is_login = true;
        return view("pendaftaran", ['is_login' => $is_login]);
    }
    public function login()
    {
        $is_login = true;
        return view("login", ['is_login' => $is_login]);
    }
    public function lupaPassword()
    {
        $is_login = true;
        return view("lupaPassword", ['is_login' => $is_login]);
    }
    public function resetPassword()
    {
        $is_login = true;
        return view("resetPassword", ['is_login' => $is_login]);
    }
    public function loginPetugas()
    {
        $is_login = true;
        return view("login-petugas", ['is_login' => $is_login]);
    }
}
