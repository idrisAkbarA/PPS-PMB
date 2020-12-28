<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    public function ujian()
    {
        return $this->hasOne('App\Ujian');
    }
    protected $casts = [
        'set_pertanyaan' => 'object',
        'set_jawaban_mhs' => 'array',
    ];
}
