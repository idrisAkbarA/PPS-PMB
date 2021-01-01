<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $guarded = ['id'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'nama_jurusan',
    ];

    public static function boot()
    {
        parent::boot();

        // Update jurusan if default category deleted
        self::deleting(function ($model) {
            $jurusan = $model->jurusan()->first();

            if ($jurusan->kat_tka_default == $model->id) {
                $jurusan->update(['kat_tka_default' => null]);
            }
            if ($jurusan->kat_tkj_default == $model->id) {
                $jurusan->update(['kat_tkj_default' => null]);
            }
        });
    }

    // Getters
    public function getNamaJurusanAttribute()
    {
        return $this->jurusan()
            ->first()
            ->nama;
    }

    public static function getAll($jurusan_id = null)
    {
        $kategori = self::latest()
            ->when($jurusan_id, function ($q) use ($jurusan_id) {
                return $q->where('jurusan_id', $jurusan_id);
            })

            ->get();

        return $kategori;
    }

    // Setters
    public static function setKategoriInJurusan($jurusan_id, $categories)
    {
        $categoriesToDelete = Kategori::where('jurusan_id', $jurusan_id)->pluck('id', 'id');

        foreach ($categories as $category) {
            $kategori = Kategori::updateOrCreate(
                ['id' => optional($category)['id']],
                [
                    'jurusan_id' => $jurusan_id,
                    'nama' => $category['nama'],
                    'deskripsi' => $category['deskripsi']
                ]
            );

            if (!empty($categoriesToDelete)) {
                unset($categoriesToDelete[$kategori->id]);
            }
        }

        // delete category
        if (count($categoriesToDelete)) {
            Kategori::whereIn('id', $categoriesToDelete)->delete();
        }
    }

    // Relations
    public function ujian_tka()
    {
        return $this->hasMany('App\Ujian', 'kat_tka_id');
    }

    public function ujian_tkj()
    {
        return $this->hasMany('App\Ujian', 'kat_tkj_id');
    }

    public function jurusan()
    {
        return $this->belongsTo('App\Jurusan');
    }
}
