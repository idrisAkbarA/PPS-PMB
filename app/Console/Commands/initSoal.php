<?php

namespace App\Console\Commands;

use App\BankSoal;
use Illuminate\Console\Command;
use App\UserClnMhs;
use App\Periode;
use App\Ujian;
use App\Soal;

class initSoal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'iniSoal';

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
            $tka_id = $value->kat_tka_id;
            $tkj_id = $jurusan->kat_tka_id;
            $jum_tka = Periode::find($value->periode_id)->jumlah_tka;
            $jum_tkd = Periode::find($value->periode_id)->jumlah_tkj;
            self::generate($jurusan, $tka_id, $tkj_id, $jum_tka, $jum_tkd);
        }
    }
    public function generate($jurusan_id, $kat_tka_id, $kat_tkj_id, $jumlah_tka, $jumlah_tkj)
    {
        $soalTKA = BankSoal::where([
            'type' => 'tka',
            'jurusan_id' => $jurusan_id,
            'kategori_id' => $kat_tka_id
        ])->get();
        $soalTKj = BankSoal::where([
            'type' => 'tkj',
            'jurusan_id' => $jurusan_id,
            'kategori_id' => $kat_tkj_id
        ])->get();
    }
    private function set($soal, $jumlah)
    {
        $soal_id_listed = [];
        $soal_merged = [];
        for ($i = 0; $i < $jumlah; $i++) {
            $randomNumber = rand(0, $soal - 1);
            foreach ($soal_id_listed as $key => $value) {
                if ($value == $randomNumber) { }
            }
            $tempSoal = $soal;
        }
    }
}
