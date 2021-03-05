<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'jadwal_ujian' => 'array'
    ];

    public static function boot()
    {
        parent::boot();

        // Create category per jurusan
        self::created(function ($model) {
            $allJurusan = Jurusan::all();

            foreach ($allJurusan as $jurusan) {
                if ($jurusan->kuota_kelas_default) {
                    $model->kuota_kelas()->create([
                        'jurusan_id' => $jurusan->id,
                        'kuota' => $jurusan->kuota_kelas_default
                    ]);
                }
            }
        });

        // Delete category in periode
        self::deleting(function ($model) {
            $categories = $model->kategori()->get();
            foreach ($categories as $category) {
                $category->delete();
            }

            $kuota = $model->kuota_kelas()->get();
            foreach ($kuota as $row) {
                $row->delete();
            }
        });
    }

    // Getters
    public static function getActive()
    {
        $currentPeriode = self::with('kategori')
            ->where('is_active', 1)
            ->first();
        if (empty($currentPeriode)) {
            $currentPeriode = null;
        }
        return $currentPeriode;
    }

    public function getKategori($jurusan_id = null)
    {
        // Get categories of periode object
        return $this->kategori()
            ->when($jurusan_id, function ($q) use ($jurusan_id) {
                return $q->where('jurusan_id', $jurusan_id);
            })->get();
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

    public function getTemuRamah()
    {
        return $this->temu_ramah()
            ->get();
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

    public function setKategori($jurusan)
    {
        foreach ($jurusan as $item) {
            $komposisi_tka = [];
            $komposisi_tkj = [];
            foreach ($item['kategori'] as $row) {
                $tmp = ['kategori_id' => $row['id'], 'nama_kategori' => $row['nama'], 'jumlah' => $row['jumlah_tka']];
                array_push($komposisi_tka, $tmp);
                $tmp = ['kategori_id' => $row['id'], 'nama_kategori' => $row['nama'], 'jumlah' => $row['jumlah_tkj']];
                array_push($komposisi_tkj, $tmp);
            }

            $this->kategori()->updateOrCreate(
                [
                    'periode_id' => $this->id,
                    'jurusan_id' => $item['id']
                ],
                [
                    'jurusan_id' => $item['id'],
                    'komposisi_tka' => $komposisi_tka,
                    'komposisi_tkj' => $komposisi_tkj,
                ]
            );
        }
    }

    // Relations
    public function ujian()
    {
        return $this->hasMany('App\Ujian');
    }

    public function kategori()
    {
        return $this->hasMany('App\KatJurusanPerPeriode');
    }
    public function kuota_kelas()
    {
        return $this->hasMany('App\KuotaKelasPerPeriode');
    }

    public function temu_ramah()
    {
        return $this->hasMany('App\JadwalTR');
    }
}
