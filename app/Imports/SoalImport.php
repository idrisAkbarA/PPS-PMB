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
    private $errors = [];
    public function model(array $row)
    {
        try {
            //code...
            if ($row['pertanyaan'] != null && $row['pertanyaan'] != '') {

                ++$this->rows;
                $pilihan_ganda = [
                    ['pilihan' => 'A', 'text' => strval($row['pilihan_a'])],
                    ['pilihan' => 'B', 'text' => strval($row['pilihan_b'])],
                    ['pilihan' => 'C', 'text' => strval($row['pilihan_c'])],
                    ['pilihan' => 'D', 'text' => strval($row['pilihan_d'])],
                    ['pilihan' => 'E', 'text' => strval($row['pilihan_e'])],
                ];
                // dd($row);
                $jurusan = Jurusan::where(['nama', $row['jurusan']])->first();
                $jurusan_id = $jurusan->id;
                $kategori = Kategori::where(['nama' => $row['kategori'], 'jurusan_id' => $jurusan_id])->first();
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
            self::rowError($this->rows, $row);
            // dd($row);
            // dd($th);
            // dd($this->rows);
        }
    }
    public function getRowCount(): int
    {
        return $this->rows;
    }

    public function getError()
    {
        return $this->errors;
    }

    public function rowError($index, $row)
    {
        $tmp = ['Baris_ke' => $index + 1, 'Data' => $row];
        array_push($this->errors, $tmp);
    }
}
