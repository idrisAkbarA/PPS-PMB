<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankSoal extends Model
{
    protected $casts = [
        'pilihan_ganda' => 'object',
    ];
    public function jurusan()
    {
        $this->belongsTo('App\Jurusan');
    }
    public function kategori()
    {
        $this->belongsTo('App\Kategori');
    }
}
