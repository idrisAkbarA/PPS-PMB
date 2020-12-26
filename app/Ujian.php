<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    public function soal()
    {
        return $this->hasOne('App\Soal');
    }
    public function jurusan()
    {
        return $this->belongsTo('App\Jurusan');
    }
    public function periode()
    {
        return $this->belongsTo('App\Periode');
    }
    public function user_cln_mhs()
    {
        return $this->belongsTo('App\UserClnMhs');
    }
    public function ujian_tka()
    {
        return $this->belongsTo('App\Kategori', 'id', 'kat_tka_id');
    }
    public function ujian_tkj()
    {
        return $this->belongsTo('App\Kategori', 'id', 'kat_tkj_id');
    }
}
