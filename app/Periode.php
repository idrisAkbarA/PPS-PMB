<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Getters
    public static function getActive()
    {
        $currentPeriode = self::where('is_active', 1)->first();
        return $currentPeriode;
    }

    public function getUjian($jurusan_id = null, $pembayaran = null, $status = null)
    {
        return $this->ujian()
            ->with('user_cln_mhs', 'jurusan')
            ->when($jurusan_id, function ($q) use ($jurusan_id) {
                return $q->where('jurusan_id', $jurusan_id);
            })
            ->when($pembayaran, function ($q) use ($pembayaran) {
                if ($pembayaran == 'Lunas') {
                    return $q->whereNotNull('lunas_at');
                }
                return $q->whereNull('lunas_at');
            })
            ->when($status, function ($q) use ($status) {
                if ($status == 'Lulus') {
                    return $q->whereNotNull('lulus_at');
                } else if ($status == 'Tidak Lulus') {
                    return $q->whereNotNull(['tka_ended', 'tkj_ended'])
                        ->whereNull('lulus_at');
                }
                return $q->whereNull(['tka_ended', 'tkj_ended']);
            })->get();
    }

    // Setters
    public function setIsActiveAttribute($value)
    {
        if ($value) {
            $allperiode = self::where('id', '!=', $this->id);
            $allperiode->update(['is_active' => 0]);
        }
        $this->attributes['is_active'] = $value;
    }

    // Relations
    public function ujian()
    {
        return $this->hasMany('App\Ujian');
    }
}
