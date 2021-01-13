<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    protected $guarded = ['id'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'nama_pendaftar',
        'nama_jurusan',
        'nama_periode',
        'status_kelulusan'
    ];

    // Getters
    public function getNamaPendaftarAttribute()
    {
        return $this->user_cln_mhs()
            ->first()
            ->nama;
    }

    public function getNamaJurusanAttribute()
    {
        return $this->jurusan()
            ->first()
            ->nama;
    }

    public function getNamaPeriodeAttribute()
    {
        return $this->periode()
            ->first()
            ->nama;
    }

    public function getStatusKelulusanAttribute()
    {
        $status = 'Belum Ujian';
        if ($this->lulus_at) {
            $status = 'Lulus';
        } else if ($this->tka_ended && $this->tkj_ended) {
            $status = 'Tidak Lulus';
        }

        return $status;
    }

    // Setters
    public function setIsLulusTka($value)
    {
        if ($value && $this->is_lulus_tkj) {
            $this->attributes['lulus_at'] = today();
        }
        $this->attributes['is_lulus_tka'] = $value;
    }

    public function setIsLulusTkj($value)
    {
        if ($value && $this->is_lulus_tka) {
            $this->attributes['lulus_at'] = today();
        }
        $this->attributes['is_lulus_tkj'] = $value;
    }

    // Relations
    public function soal()
    {
        return $this->hasOne('App\Soal');
    }
    public function jurusan()
    {
        return $this->belongsTo('App\Jurusan');
    }
    public function periode()
    {
        return $this->belongsTo('App\Periode');
    }
    public function user_cln_mhs()
    {
        return $this->belongsTo('App\UserClnMhs');
    }
    public function ujian_tka()
    {
        return $this->belongsTo('App\Kategori', 'id', 'kat_tka_id');
    }
    public function ujian_tkj()
    {
        return $this->belongsTo('App\Kategori', 'id', 'kat_tkj_id');
    }
}
