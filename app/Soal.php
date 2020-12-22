<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    public function ujian()
    {
        $this->hasOne('App\Ujian');
    }
}
