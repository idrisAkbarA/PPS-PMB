<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    protected $guarded = ['id'];

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
            $model->update([
                'kat_tka_default' => $kategori->id,
                'kat_tkj_default' => $kategori->id
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
    public function kat_tkj()
    {
        return $this->hasOne('App\Kategori', 'id', 'kat_tkj_default');
    }
    public function kat_tka()
    {
        return $this->hasOne('App\Kategori', 'id', 'kat_tka_default');
    }
}
