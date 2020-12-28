<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/authenticate/server/{role}', 'AuthController@loginServer'); // roles are 'cln_mahasiswa' and 'petugas'
Route::get('/soal/{id}/{type}', 'SoalController@get'); // id = row id of soal, type = tka or tkj
Route::post('/soal/set-jawaban', 'SoalController@setJawaban');
Route::post('/soal/calc-score', 'SoalController@calcScore');
Route::post('/soal/test', 'SoalController@test');
Route::post('/authenticate/{role}', 'AuthController@login'); // roles are 'cln_mahasiswa' and 'petugas'
Route::post('/auth-is-login/{role}', 'AuthController@isLogin'); // roles are 'cln_mahasiswa' and 'petugas'
Route::post('/logout-petugas', 'AuthController@logoutPetugas'); // roles are 'cln_mahasiswa' and 'petugas'
Route::post('/logout', 'AuthController@logout'); // roles are 'cln_mahasiswa' and 'petugas'
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Calon Mahasiswa Routes
Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/{user?}', 'UserClnMhsController@show')->name('show');
});

// Periode Routes
Route::prefix('periode')->name('periode.')->group(function () {
    Route::get('/', 'PeriodeController@index')->name('index');
});
