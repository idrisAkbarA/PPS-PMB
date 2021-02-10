<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'cln_mhs' => 'object',
    ];
}
