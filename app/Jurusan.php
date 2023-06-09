<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    protected $guarded = ['id'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'kat_per_periode',
    ];

    protected $casts = [
        'komposisi_tka_default' => 'object',
        'komposisi_tkj_default' => 'object',
    ];

    public static function boot()
    {
        parent::boot();

        // Create default categories
        self::created(function ($model) {
            $model->kategori()->createMany([
                [
                    'nama' => 'Mudah',
                    'deskripsi' => 'Soal dengan tingkat kesulitan Mudah'
                ],
                [
                    'nama' => 'Menengah',
                    'deskripsi' => 'Soal dengan tingkat kesulitan Menengah'
                ],
                [
                    'nama' => 'Sulit',
                    'deskripsi' => 'Soal dengan tingkat kesulitan Sulit'
                ]
            ]);
            $kategori = $model->kategori()->get();
            $kategori[0]->jumlah_tka = 10;
            $kategori[0]->jumlah_tkj = 10;
            $model->update([
                'komposisi_tka_default' => $kategori,
                'komposisi_tkj_default' => $kategori
            ]);
        });

        // Delete categories
        self::deleting(function ($model) {
            $categories = $model->kategori()->get();
            foreach ($categories as $category) {
                $category->delete();
            }
            $kat_periode = $model->kat_periode()->get();
            foreach ($kat_periode as $category) {
                $category->delete();
            }
        });
    }

    // Getters
    public static function getAll()
    {
        return self::with('kategori', 'tkj_default', 'tka_default')
            ->orderBy('created_at')
            ->get();
    }

    public function getKatPerPeriodeAttribute()
    {
        $currentPeriode = Periode::getActive();

        if ($currentPeriode) {
            $kategori = $currentPeriode->getKategori($this->id)->first();
            if (is_null($kategori)) {
                $kategori = collect([
                    'periode_id' => $currentPeriode->id,
                    'jurusan_id' => $this->id,
                    'kat_tka' => $this->tka_default()->first(),
                    'kat_tkj' => $this->tkj_default()->first()
                ]);
            }
            return $kategori;
        }
        return null;
    }

    // Setters
    public function setKomposisiTkaDefaultAttribute($value)
    {
        $komposisi = [];
        foreach ($value as $row) {
            if ($row['jumlah_tka'] > 0) {
                $tmp = ['kategori_id' => $row['id'], 'nama_kategori' => $row['nama'], 'jumlah' => $row['jumlah_tka'] ?? 0,];
                array_push($komposisi, $tmp);
            }
        }
        $this->attributes['komposisi_tka_default'] = json_encode($komposisi);
    }

    public function setKomposisiTkjDefaultAttribute($value)
    {
        $komposisi = [];
        foreach ($value as $row) {
            if ($row['jumlah_tkj'] > 0) {
                $tmp = ['kategori_id' => $row['id'], 'nama_kategori' => $row['nama'], 'jumlah' => $row['jumlah_tkj'] ?? 0,];
                array_push($komposisi, $tmp);
            }
        }
        $this->attributes['komposisi_tkj_default'] = json_encode($komposisi);
    }

    // Relations
    public function ujian()
    {
        return $this->hasMany('App\Ujian');
    }

    public function bank_soal()
    {
        return $this->hasMany('App\BankSoal');
    }

    public function kategori()
    {
        return $this->hasMany('App\Kategori');
    }

    public function kat_periode()
    {
        return $this->hasMany('App\KatJurusanPerPeriode');
    }

    public function tkj_default()
    {
        return $this->hasOne('App\Kategori', 'id', 'kat_tkj_default');
    }

    public function tka_default()
    {
        return $this->hasOne('App\Kategori', 'id', 'kat_tka_default');
    }
}
