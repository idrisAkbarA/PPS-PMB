<?php

namespace App\Exports;

use App\Library\ExportData;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserWhoPassedExamExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct(
        $periode_id
    ) {
        $this->periode_id = $periode_id;
    }
    public function collection()
    {
        return ExportData::collection($this->periode_id);
    }
    public function headings(): array
    {
        return [
            'username',
            'nomor_registrasi',
            'nama',
            'nik',
            'password',
            'tempat_lahir',
            'tanggal_lahir',
            'tahun_ajaran',
            'kode_jurusan',
            'id_jurusan',
            'jalur_masuk',
        ];
    }
}
