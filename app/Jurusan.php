<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    public function ujian()
    {
        $this->hasMany('App\Ujian');
    }
    public function bank_soal()
    {
        $this->hasMany('App\BankSoal');
    }
}
