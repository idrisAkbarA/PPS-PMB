<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    public function soal()
    {
        $this->hasOne('App\Soal');
    }
    public function jurusan()
    {
        $this->belongsTo('App\Jurusan');
    }
    public function periode()
    {
        $this->belongsTo('App\Periode');
    }
    public function user_cln_mhs()
    {
        $this->belongsTo('App\UserClnMhs');
    }
    public function ujian_tka()
    {
        $this->belongsTo('App\Kategori', 'id', 'kat_tka_id');
    }
    public function ujian_tkd()
    {
        $this->belongsTo('App\Kategori', 'id', 'kat_tkd_id');
    }
}
