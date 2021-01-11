<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankSoal extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'pilihan_ganda' => 'object',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'nama_kategori',
        'nama_jurusan',
    ];

    // Getters
    public function getTypeAttribute($value)
    {
        return strtoupper($value);
    }
    public function getNamaKategoriAttribute()
    {
        if (is_null($this->kategori())) {
            return null;
        }

        return $this->kategori()
            ->first()
            ->nama;
    }

    public function getNamaJurusanAttribute()
    {
        if (is_null($this->jurusan())) {
            return null;
        }

        return $this->jurusan()
            ->first()
            ->nama;
    }

    public static function getByKategori($kategori_id = null, $jurusan_id = null)
    {
        $data = [
            'tka' => self::getTkaByKategori($kategori_id, $jurusan_id),
            'tkj' => self::getTkjByKategori($kategori_id, $jurusan_id)
        ];
        return $data;
    }

    public static function getTkaByKategori($kategori_id = null, $jurusan_id = null)
    {
        return self::where('type', 'tka')
            ->when($kategori_id, function ($q) use ($kategori_id) {
                return $q->where('kategori_id', $kategori_id);
            })
            ->when(!$kategori_id && $jurusan_id, function ($q) use ($jurusan_id) {
                return $q->where('jurusan_id', $jurusan_id);
            })
            ->latest()
            ->paginate(5);
    }

    public static function getTkjByKategori($kategori_id, $jurusan_id)
    {
        return self::where('type', 'tkj')
            ->when($kategori_id, function ($q) use ($kategori_id) {
                return $q->where('kategori_id', $kategori_id);
            })
            ->when(!$kategori_id && $jurusan_id, function ($q) use ($jurusan_id) {
                return $q->where('jurusan_id', $jurusan_id);
            })
            ->latest()
            ->paginate(5);
    }

    // Setters
    public function setTypeAttribute($value)
    {
        $this->attributes['type'] = strtolower($value);
    }

    // Relations
    public function jurusan()
    {
        return $this->belongsTo('App\Jurusan');
    }

    public function kategori()
    {
        return $this->belongsTo('App\Kategori');
    }
}
