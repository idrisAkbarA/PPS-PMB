<?php

namespace App\Console\Commands;

use App\BankSoal;
use Illuminate\Console\Command;
use App\UserClnMhs;
use App\Periode;
use App\Ujian;
use App\Soal;
use App\Library\SoalUjian;

class initSoal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'initSoal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'initialize soal';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $ujians = Ujian::all();
        foreach ($ujians as $key => $value) {
            $jurusan = $value->jurusan_id;
            $komposisi_tka = $value->komposisi_tka;
            $komposisi_tkj = $value->komposisi_tkj;
            $ujian_id = $value->id;
            $jum_tka = Periode::find($value->periode_id)->jumlah_tka;
            $jum_tkd = Periode::find($value->periode_id)->jumlah_tkj;
            $soalUjian = new SoalUjian;
            $soalUjian->generate($jurusan, $komposisi_tka, $komposisi_tkj, $jum_tka, $jum_tkd, $ujian_id);
        }
        echo "Soal Ujian Stored\n";
    }
}
