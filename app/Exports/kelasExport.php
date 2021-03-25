<?php

namespace App\Exports;

use App\Kelas;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class kelasExport implements FromView, ShouldAutoSize
{
    private $jurusan_id;
    private $periode_id;

    public function __construct($jurusan_id, $periode_id)
    {
        $this->jurusan_id = $jurusan_id;
        $this->periode_id = $periode_id;
    }

    public function view(): View
    {
        $data = Kelas::with('kategori')->get();


        return view('kelasExcel', ['data' => $data]);
    }
}
