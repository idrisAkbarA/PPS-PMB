<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    protected $guarded = ['id'];

    public function ujian()
    {
        return $this->hasMany('App\Ujian');
    }
    public function bank_soal()
    {
        return $this->hasMany('App\BankSoal');
    }
    public function kategori()
    {
        return $this->hasMany('App\Kategori');
    }
    public function kat_tkj_default()
    {
        return $this->hasMany('App\Kategori', 'id', 'kat_tkj_default');
    }
    public function kat_tka_default()
    {
        return $this->hasMany('App\Kategori', 'id', 'kat_tka_default');
    }
}
