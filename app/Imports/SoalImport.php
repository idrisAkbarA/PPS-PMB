<?php

namespace App\Imports;

use App\BankSoal;
use App\Jurusan;
use App\Kategori;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SoalImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    private $rows = 0;
    public function model(array $row)
    {
        ++$this->rows;
        $pilihan_ganda = [
            ['pilihan' => 'A', 'text' => $row['pilihan_a']],
            ['pilihan' => 'B', 'text' => $row['pilihan_b']],
            ['pilihan' => 'C', 'text' => $row['pilihan_c']],
            ['pilihan' => 'D', 'text' => $row['pilihan_d']],
            ['pilihan' => 'E', 'text' => $row['pilihan_e']],
        ];
        // $jurusan_id = Jurusan::where('nama', $row['jurusan'])->get();
        // dd($jurusan_id);
        $jurusan = Jurusan::where('nama', $row['jurusan'])->first();
        $jurusan_id = $jurusan->id;
        $kategori = Kategori::where('nama', $row['kategori'])->first();
        $kategori_id = $kategori->id;
        // dd($kategori_id);
        BankSoal::create([
            'pertanyaan' => $row['pertanyaan'],
            'pilihan_ganda' => $pilihan_ganda,
            'jawaban' => $row['jawaban'],
            'jurusan_id' => $jurusan_id,
            'kategori_id' => $kategori_id,
            'type' => strtolower($row['tipe_soal'])
        ]);
    }
    public function getRowCount(): int
    {
        return $this->rows;
    }
}
