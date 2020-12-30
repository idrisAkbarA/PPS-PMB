<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $guarded = ['id'];

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
