<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    public function ujian_tka()
    {
        $this->hasMany('App\Ujian', 'kat_tka_id');
    }
    public function ujian_tkd()
    {
        $this->hasMany('App\Ujian', 'kat_tkd_id');
    }
}
