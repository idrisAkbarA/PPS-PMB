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
Route::middleware('auth:cln_mahasiswa')->get('/soal/{ujian_id}/{type}/{soal_id?}', 'SoalController@get'); // id = row id of soal, type = tka or tkj
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
Route::middleware('auth:cln_mahasiswa')->post('/ujian/init', 'UjianController@initUjian');
Route::middleware('auth:cln_mahasiswa')->post('/ujian/generate-pembayaran', 'UjianController@generatePembayaran');
Route::middleware('auth:cln_mahasiswa')->put('/user/update', 'UserClnMhsController@selfUpdate');
Route::middleware('auth:cln_mahasiswa')->post('/user/store-file', 'UserClnMhsController@storeFile');
Route::post('/ujian/pay', 'UjianController@pay');
Route::middleware('auth:cln_mahasiswa')->post('/ujian/check-pembayaran', 'UjianController@checkPembayaran');
Route::middleware('auth:cln_mahasiswa')->post('/ujian/get-pendaftaran', 'UjianController@getPendaftaran');
Route::post('/ujian/test', 'UjianController@test');
Route::get('/ujian/laporan', 'UjianController@laporan');
Route::get('/ujian/dashboard', 'UjianController@dashboard');
Route::post('/forget-password', 'ForgotPasswordController@postEmail');
Route::post('/reset-password', 'ResetPasswordController@updatePassword');

// Petugas Routes
Route::prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/', 'UserPetugasController@index')->name('index');
    Route::post('/', 'UserPetugasController@store')->name('store');
    Route::put('/{petugas?}', 'UserPetugasController@update')->name('update');
    Route::delete('/{petugas?}', 'UserPetugasController@destroy')->name('destroy');
});

// Periode Routes
Route::prefix('periode')->name('periode.')->group(function () {
    Route::get('/', 'PeriodeController@index')->name('index');
    Route::get('/current', 'PeriodeController@show')->name('current');
    Route::post('/', 'PeriodeController@store')->name('store');
    Route::put('/{periode?}', 'PeriodeController@update')->name('update');
    Route::delete('/{periode?}', 'PeriodeController@destroy')->name('destroy');
});

// Jurusan Routes
Route::prefix('jurusan')->name('jurusan.')->group(function () {
    Route::get('/', 'JurusanController@index')->name('index');
    Route::post('/', 'JurusanController@store')->name('store');
    Route::put('/{jurusan?}', 'JurusanController@update')->name('update');
    Route::delete('/{jurusan?}', 'JurusanController@destroy')->name('destroy');
});

// Calon Mahasiswa Routes
Route::prefix('pendaftar')->name('pendaftar.')->group(function () {
    Route::get('/', 'UserClnMhsController@index')->name('index');
    Route::get('/{user}', 'UserClnMhsController@show')->name('show');
    Route::put('/{user}', 'UserClnMhsController@update')->name('update');
});

// Ujian Routes
Route::prefix('ujian')->name('ujian.')->group(function () {
    Route::get('/', 'UjianController@index')->name('index');
    Route::put('/{ujian?}', 'UjianController@update');
});

// Kategori Routes
Route::prefix('kategori')->name('kategori.')->group(function () {
    Route::get('/', 'KategoriController@index')->name('index');
    Route::post('/', 'KategoriController@store')->name('store');
    Route::get('/{jurusan?}', 'KategoriController@getByJurusan')->name('get-jurusan');
    Route::post('/{jurusan?}', 'KategoriController@storeInJurusan')->name('store-jurusan');
    Route::put('/{kategori?}', 'KategoriController@update')->name('update');
    Route::delete('/{kategori?}', 'KategoriController@destroy')->name('destroy');
});

// Bank Soal Routes
Route::prefix('bank-soal')->name('bank-soal.')->group(function () {
    Route::get('/', 'BankSoalController@index')->name('index');
    Route::get('/tka', 'BankSoalController@getSoalTKA')->name('tka');
    Route::get('/tkj', 'BankSoalController@getSoalTKJ')->name('tkj');
    Route::post('/', 'BankSoalController@store')->name('store');
    Route::post('/import', 'BankSoalController@importExcel')->name('import');
    Route::get('/download-template', 'BankSoalController@generateTemplate')->name('template');
    Route::get('/jumlah', 'BankSoalController@jumlahAkhir')->name('jumlah');
    Route::put('/{soal?}', 'BankSoalController@update')->name('update');
    Route::delete('/{soal?}', 'BankSoalController@destroy')->name('destroy');
});

// Kategori per Periode Routes
Route::prefix('kategori-periode')->name('kategori.')->group(function () {
    Route::post('/', 'KatJurusanPerPeriodeController@store')->name('store');
});

// Temu Ramah Routes
Route::middleware('auth:petugas,cln_mahasiswa')->prefix('temu-ramah')->name('temu-ramah')->group(function () {
    Route::get('/', 'JadwalTRController@index')->name('index');
    Route::post('/', 'JadwalTRController@store')->name('store');
    Route::put('/{jadwal}', 'JadwalTRController@update')->name('update');
    Route::delete('/{jadwal}', 'JadwalTRController@destroy')->name('destroy');
});
Route::prefix('ci')->name('ci')->group(function () {
    Route::get('/pull', 'CIController@pull')->name('pull');
    Route::get('/init-all-sequences', 'CIController@initAllSequences')->name('pull');
    Route::post('/custom', 'CIController@custom')->name('pull');
    // Route::post('/', 'JadwalTRController@store')->name('store');
    // Route::put('/{jadwal}', 'JadwalTRController@update')->name('update');
    // Route::delete('/{jadwal}', 'JadwalTRController@destroy')->name('destroy');
});
Route::prefix('cumlaude')->name('cumlaude')->group(function () {
    Route::get('/', 'CumlaudeController@index')->name('all');
    Route::get('/{periode_id}/{jurusan_id}', 'CumlaudeController@show')->name('show');
    Route::get('/init-data', 'CumlaudeController@initData')->name('initData');
    Route::post('/update', 'CumlaudeController@update')->name('show');
});
