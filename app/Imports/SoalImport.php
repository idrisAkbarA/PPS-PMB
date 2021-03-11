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
        try {
            //code...
            if ($row['pertanyaan'] != null && $row['pertanyaan'] != '') {

                ++$this->rows;
                $pilihan_ganda = [
                    ['pilihan' => 'A', 'text' => $row['pilihan_a']],
                    ['pilihan' => 'B', 'text' => $row['pilihan_b']],
                    ['pilihan' => 'C', 'text' => $row['pilihan_c']],
                    ['pilihan' => 'D', 'text' => $row['pilihan_d']],
                    ['pilihan' => 'E', 'text' => $row['pilihan_e']],
                ];
                // dd($row);
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
        } catch (\Throwable $th) {
            self::rowError($this->rows, $this->row);
            // dd($row);
            // dd($th);
            // dd($this->rows);
        }
    }
    public function getRowCount(): int
    {
        return $this->rows;
    }
    public function rowError($index, $row)
    {
        return ['Baris_ke' => $index + 1, 'Data' => $row];
    }
}
