<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    public function ujian()
    {
        return $this->hasOne('App\Ujian');
    }
}
