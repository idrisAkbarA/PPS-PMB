<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KatJurusanPerPeriode extends Model
{
    protected $guarded = ['id'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'nama_jurusan',
        'nama_kat_tka',
        'nama_kat_tkj',
    ];

    // Getters
    public function getNamaJurusanAttribute()
    {
        return $this->jurusan()->first()->nama;
    }

    public function getNamaKatTkaAttribute()
    {
        return $this->kat_tka()->first()->nama;
    }

    public function getNamaKatTkjAttribute()
    {
        return $this->kat_tkj()->first()->nama;
    }

    // Relations
    public function periode()
    {
        return $this->belongsTo('App/Periode');
    }

    public function jurusan()
    {
        return $this->belongsTo('App\Jurusan');
    }

    public function kat_tka()
    {
        return $this->belongsTo('App\Kategori', 'kat_tka_id');
    }

    public function kat_tkj()
    {
        return $this->belongsTo('App\Kategori', 'kat_tkj_id');
    }
}
