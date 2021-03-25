<?php

namespace App\Exports;

use App\Periode;
use App\Ujian;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class pendaftaranExport implements FromView, ShouldAutoSize
{
    private $jurusan_id;
    private $periode_id;

    public function __construct(
        $jurusan_id = null,
        $periode_id = null
    ) {
        $this->jurusan_id = $jurusan_id;
        $this->periode_id = $periode_id;
    }

    public function view(): View
    {

        $currentPeriode = Periode::getActive();
        if ($this->periode_id) {
            $currentPeriode = Periode::find($this->periode_id);
        }

        if (!is_null($currentPeriode)) {
            $pendaftaran = $currentPeriode->getUjian($this->jurusan_id, $this->pembayaran, $this->status);
        }

        $data = [
            'currentPeriode' => $currentPeriode,
            'pendaftaran' => $pendaftaran ?? []
        ];

        if ($this->periode_id) {
            $periode = Periode::select('nama')->find($this->periode_id);
            $data['periode'] = $periode;
        }
        if ($this->jurusan_id) {
            $jurusan = Periode::select('nama')->find($this->jurusan_id);
            $data['jurusan'] = $jurusan;
        }
        return view('pendaftaranExcel', ['data' => $data]);
    }
}
