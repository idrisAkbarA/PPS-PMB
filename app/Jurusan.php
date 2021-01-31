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
            $kategori = $model->kategori()->first();
            $komposisi = [
                ['kategori_id' => $kategori->id, 'nama_kategori' => $kategori->nama, 'jumlah' => 10,],
            ];
            $model->update([
                'komposisi_tka_default' => $komposisi,
                'komposisi_tkj_default' => $komposisi
            ]);
        });

        // Delete categories
        self::deleting(function ($model) {
            $categories = $model->kategori()->get();
            foreach ($categories as $category) {
                $category->delete();
            }
        });
    }

    // Getters
    public static function getAll()
    {
        return self::with('kategori', 'tkj_default', 'tka_default')
            ->latest()
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

    public function tkj_default()
    {
        return $this->hasOne('App\Kategori', 'id', 'kat_tkj_default');
    }

    public function tka_default()
    {
        return $this->hasOne('App\Kategori', 'id', 'kat_tka_default');
    }
}
