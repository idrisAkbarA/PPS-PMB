<?php

namespace App\Exports;

use App\Jurusan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class templateExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $jurusan = Jurusan::with('kategori')->get();


        return view('templateExcel', ['jurusan' => $jurusan]);
    }
}
