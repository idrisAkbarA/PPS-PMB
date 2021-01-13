<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JadwalTR extends Model
{
    protected $guarded = ['id'];

    protected $attributes = [
        'ids_cln_mhs' => '[]'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'calon_mahasiswa',
    ];

    // Getters
    public function getIdsClnMhsAttribute($value)
    {
        return json_decode($value);
    }

    public function getCalonMahasiswaAttribute()
    {
        return UserClnMhs::with('ujian')->find($this->ids_cln_mhs);
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
