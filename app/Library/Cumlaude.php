<?php

namespace App\Library;

use App\Ujian;
use App\Periode;
use Carbon\Carbon;

class Cumlaude
{
    public function all()
    {
        $periode_active = Periode::getActive();
        $cumlaudes = Ujian::select(
            'id',
            'periode_id',
            'jurusan_id',
            'user_cln_mhs_id',
            'is_lulus_tka',
            'is_lulus_tkj'
        )->with('user_cln_mhs')
            ->where([
                'periode_id' => $periode_active->id,
                'is_jalur_cumlaude' => 1,
                'is_agree' => 1
            ])
            ->orderBy('id', 'DESC')->get();
        $desiredData = self::setData($cumlaudes);
        return $desiredData;
    }
    public function get($periode_id, $jurusan_id)
    {
        $where = [
            'periode_id' => $periode_id,
            'is_jalur_cumlaude' => 1,
            'is_agree' => 1
        ];
        if ($jurusan_id != 'all') {
            $where['jurusan_id'] = $jurusan_id;
        }
        $cumlaudes = Ujian::select(
            'id',
            'periode_id',
            'jurusan_id',
            'user_cln_mhs_id',
            'is_lulus_tka',
            'is_lulus_tkj'
        )->with('user_cln_mhs')
            ->where($where)
            ->orderBy('id', 'DESC')
            ->get();
        $desiredData = self::setData($cumlaudes);
        return $desiredData;
    }
    public function setStatusLulus(bool $is_lulus, int $id)
    {
        try {
            //code...
            $ujian = Ujian::find($id);
            $ujian->is_lulus_tka = $is_lulus;
            $ujian->is_lulus_tkj = $is_lulus;
            // if ($is_lulus) {
            //     $ujian->lulus_at = Carbon::now();
            // }
            $ujian->save();

            return ['status' => true, 'message' => "Status berhasil di simpan!"];
        } catch (\Throwable $th) {
            //throw $th;
            return ['status' => false, 'message' => "Terjadi kesalahan"];
        }
    }
    private function setData($cumlaudes)
    {
        // set wether cln mhs is eligible or not or not yet verified
        foreach ($cumlaudes as $key => $value) {
            if ($value['is_lulus_tka'] === 1) {
                $value['status_code'] = 1;
                $value['status_message'] = "Lulus Verifikasi";
            }
            if ($value['is_lulus_tka'] === 0) {
                $value['status_code'] = 0;
                $value['status_message'] = "Tidak Lulus Verifikasi";
            }
            if ($value['is_lulus_tka'] === null) {
                $value['status_code'] = 2;
                $value['status_message'] = "Belum Verifikasi";
            }
            $value['link_transkip'] = $value['user_cln_mhs']['transkip'];
            unset($value['is_lulus_tka']);
            // unset($value['user_cln_mhs']);
            unset($value['status_kelulusan']);
            unset($value['is_lulus_tkj']);
        }
        return $cumlaudes;
    }
}
