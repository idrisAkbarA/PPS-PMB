<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
/---------------------------------------------------------------------------
/ All web route handled by WebURLController
/ use 'spa' method for route that handled by spa
/
>*/

// Non SPA Route
Route::get('/', 'WebURLController@landingPage');
Route::get('/pendaftaran', 'WebURLController@pendaftaran');
Route::get('/tentang', 'WebURLController@tentang');
Route::get('/petunjuk', 'WebURLController@petunjuk');
Route::get('/login', 'WebURLController@login')->name('login');
Route::get('/login-petugas', 'WebURLController@loginPetugas');
Route::post('/authenticate/{role}', 'AuthController@login'); // roles are 'cln_mahasiswa' and 'petugas'
Route::post('/daftar', 'AuthController@register')->name('register');

//SPA Route
Route::get('/user/{any}', 'WebURLController@spa')->where('any', '.*');
