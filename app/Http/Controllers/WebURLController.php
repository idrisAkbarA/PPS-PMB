<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebURLController extends Controller
{
    public function spa(){
        return view("layouts.app");
    }
}
