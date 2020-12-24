<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    public function ujian_tka()
    {
        return $this->hasMany('App\Ujian', 'kat_tka_id');
    }
    public function ujian_tkd()
    {
        return $this->hasMany('App\Ujian', 'kat_tkd_id');
    }
    public function jurusan()
    {
        return $this->belongsTo('App\Jurusan');
    }
}
