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

    protected $appends = [
        'jurusans_detail'
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
    public function getJurusansDetailAttribute()
    {
        $jurusan = Jurusan::getAll();
        $kategori = $this->getKategori();
        $kuota = $this->getKuotaKelas();
        $nominal = $this->getNominal();

        $mapped = $jurusan->map(function ($item, $key) use ($kuota, $kategori, $nominal) {
            $kuota_temp = $kuota->firstWhere('jurusan_id', $item['id']);
            $nominal_temp = $nominal->firstWhere('jurusan_id', $item['id']);
            $kategori_temp = $kategori->firstWhere('jurusan_id', $item['id']);

            $newValues = [
                'komposisi_tka' => $kategori_temp['komposisi_tka'],
                'komposisi_tkj' => $kategori_temp['komposisi_tkj'],
                'kuota_kelas' => $kuota_temp['kuota'],
                'nominal_bayar' => $nominal_temp['nominal'],
            ];
            return array_merge($item->toArray(), $newValues);
        });
        return $mapped;
    }

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
    public function getKuotaKelas($jurusan_id = null)
    {
        // Get categories of periode object
        return $this->kuota_kelas()
            ->when($jurusan_id, function ($q) use ($jurusan_id) {
                return $q->where('jurusan_id', $jurusan_id);
            })->get();
    }
    public function getNominal($jurusan_id = null)
    {
        // Get categories of periode object
        return $this->nominal()
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
    public function getKelas($jurusan_id = null)
    {
        $jurusan_ids = Jurusan::all();
        $kelas = $this->kelas()->with('jurusan')->when($jurusan_id, function ($q) use ($jurusan_id) {
            return $q->where('jurusan_id', $jurusan_id);
        })->get();

        $result = [];

        foreach ($jurusan_ids as $key => $value) {
            $newValue = $kelas->filter(function ($val) use ($value) {
                if ($val['jurusan']['id'] == $value['id']) {
                    return true;
                } else {
                    return false;
                }
            });
            $result[] = [
                'jurusan' => $value['nama'],
                'kelas' => $newValue
            ];
        }

        return $result;
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

    public function setJurusan($jurusan)
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

            $this->kuota_kelas()->updateOrCreate(
                [
                    'periode_id' => $this->id,
                    'jurusan_id' => $item['id']
                ],
                [

                    'jurusan_id' => $item['id'],
                    'kuota' => $item['kuota_kelas'] ?? $item['kuota_kelas_default']
                ]
            );

            $this->nominal()->updateOrCreate(
                [
                    'periode_id' => $this->id,
                    'jurusan_id' => $item['id']
                ],
                [

                    'jurusan_id' => $item['id'],
                    'nominal' => $item['nominal_bayar'] ?? $item['nominal_bayar_default']
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

    public function nominal()
    {
        return $this->hasMany('App\NominalJurusanPerPeriode');
    }

    public function temu_ramah()
    {
        return $this->hasMany('App\JadwalTR');
    }
    public function kelas()
    {
        return $this->hasMany('App\Kelas');
    }
}
