<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JadwalTR extends Model
{
    public function periode()
    {
        return $this->belongsTo('App\Periode');
    }
}
