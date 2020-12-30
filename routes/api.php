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
Route::post('/soal/set-lulus', 'SoalController@setLulus');
Route::post('/soal/test', 'SoalController@test');
Route::post('/authenticate/{role}', 'AuthController@login'); // roles are 'cln_mahasiswa' and 'petugas'
Route::post('/auth-is-login/{role}', 'AuthController@isLogin'); // roles are 'cln_mahasiswa' and 'petugas'
Route::post('/logout-petugas', 'AuthController@logoutPetugas');
Route::post('/logout', 'AuthController@logout');
Route::middleware('auth:petugas,cln_mahasiswa')->get('/user/{role}', 'AuthController@user');
Route::middleware('auth:cln_mahasiswa')->get('/data/init-data-cln-mhs', 'UjianController@initAllDataClnMhs');

// Calon Mahasiswa Routes
Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/{user?}', 'UserClnMhsController@show')->name('show');
});

// Periode Routes
Route::prefix('periode')->name('periode.')->group(function () {
    Route::get('/', 'PeriodeController@index')->name('index');
    Route::post('/', 'PeriodeController@store')->name('store');
    Route::put('/{periode?}', 'PeriodeController@update')->name('update');
    Route::delete('/{periode?}', 'PeriodeController@destroy')->name('delete');
});

// Jurusan Routes
Route::prefix('jurusan')->name('jurusan.')->group(function () {
    Route::get('/', 'JurusanController@index')->name('index');
    Route::post('/', 'JurusanController@store')->name('store');
    Route::put('/{jurusan?}', 'JurusanController@update')->name('update');
    Route::delete('/{jurusan?}', 'JurusanController@destroy')->name('delete');
});
