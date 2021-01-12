<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JadwalTR extends Model
{
    protected $guarded = ['id'];

    protected $attributes = [
        'ids_cln_mhs' => '[]'
    ];

    // Getters
    public function getIdsClnMhsAttribute($value)
    {
        return json_decode($value);
    }

    // Setters
    public function setIdsClnMhsAttribute($value)
    {
        $this->attributes['ids_cln_mhs'] = json_encode($value);
    }

    // Relations
    public function periode()
    {
        return $this->belongsTo('App\Periode');
    }
}
