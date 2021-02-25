<?php

namespace App\Library;

use App\Ujian;
use Illuminate\Support\Carbon;

class ReportUjian
{
    private $ujians;

    public function __construct($ujians = null)
    {
        $this->ujians = $ujians;
    }
    public function totalGagal($ujians = null)
    {
        $ujians ?? $this->ujians;
        $today = Carbon::now();
        $total = 0;
        foreach ($ujians as $key => $value) {
            $deadline = new Carbon($value->batas_ujian);
            $tka_status = $value->is_lulus_tka;
            $tkj_status = $value->is_lulus_tkj;
            if ($tka_status === 1 && $tkj_status === 1) continue;
            if ($tka_status === 0 || $tkj_status === 0) {
                $total += 1;
                continue;
            }
            if ($today->gt($deadline)) {
                $total += 1;
                continue;
            };
        }
        return $total;
    }
}
