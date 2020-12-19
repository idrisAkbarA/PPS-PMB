<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class WebURLController extends Controller
{
    public function spa(){
        return view("layouts.app");
    }
    public function landingPage(){
        $is_landing_page = true;
        return view("landingPage",['is_landing_page'=>$is_landing_page]);
    }
    public function pendaftaran(){
        return view("pendaftaran");
    }
    public function login(){
        return view("login");
    }
}
