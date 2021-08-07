<?php

namespace App\Library;

use App\Ujian;

class ExportData
{
    public static function collection($periode_id)
    {
        $rawData = Ujian::with([
            'jurusan',
            'user_cln_mhs',
            'periode'
        ])
            ->where('periode_id', $periode_id)
            ->whereNotNull('lulus_at')
            ->get();
        $result = $rawData->map(function ($item, $key) {
            return [
                'username' => $item->final_id,
                'nomor_registrasi' => $item->final_id,
                'nama' => $item->user_cln_mhs->nama,
                'nik' => $item->user_cln_mhs->nik,
                'password' => $item->user_cln_mhs->tgl_lahir,
                'tempat_lahir' => $item->user_cln_mhs->tempat_lahir,
                'tanggal_lahir' => $item->user_cln_mhs->tgl_lahir,
                'tahun_ajaran' => $item->periode->tahun,
                'kode_jurusan' => $item->jurusan->kode_jurusan,
                'id_jurusan' => $item->jurusan_id,
                'jalur_masuk' => 12

            ];
        });
        return $result;
    }
    public static function sireg($periode_id)
    {
        # code...
    }
}
