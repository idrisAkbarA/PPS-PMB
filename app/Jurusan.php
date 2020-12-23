<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
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
}
