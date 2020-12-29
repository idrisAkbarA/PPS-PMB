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
    public function kat_tkj()
    {
        return $this->hasOne('App\Kategori', 'id', 'kat_tkj_default');
    }
    public function kat_tka()
    {
        return $this->hasOne('App\Kategori', 'id', 'kat_tka_default');
    }
}
