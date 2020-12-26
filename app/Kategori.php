<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    public function ujian_tka()
    {
        return $this->hasMany('App\Ujian', 'kat_tka_id');
    }
    public function ujian_tkj()
    {
        return $this->hasMany('App\Ujian', 'kat_tkj_id');
    }
    public function jurusan()
    {
        return $this->belongsTo('App\Jurusan');
    }
}
