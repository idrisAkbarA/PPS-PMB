<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    protected $casts = [
        'is_active' => 'boolean',
    ];
}
