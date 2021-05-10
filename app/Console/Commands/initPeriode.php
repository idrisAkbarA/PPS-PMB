<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jurusan;
use App\Periode;
use App\KatJurusanPerPeriode;
use Faker\Factory as Faker;
use Illuminate\Support\Carbon;

class initPeriode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'initPeriode';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'initialize mock periode ';

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
        $thisYear =  Carbon::now()->year;
        $nextYear = Carbon::now()->addYear()->year;
        $lastYear = Carbon::now()->subYear()->year;
        $faker = Faker::create("id_ID");

        $periode = new periode;
        $periode->nama = $thisYear . "/" . $nextYear;
        $periode->awal_periode = $thisYear . '-01-01';
        $periode->akhir_periode = $thisYear . '-12-30';
        $periode->tahun = $thisYear;
        $periode->range_ujian = 10;
        $periode->durasi_ujian = 5;
        $periode->durasi_soal = 15;
        $periode->syarat_ipk = 3.0;
        $periode->syarat_bhs_arab = 350;
        $periode->syarat_bhs_inggris = 350;
        $periode->awal_temu_ramah = $thisYear . '-5-01';
        $periode->akhir_temu_ramah = $thisYear . '-5-30';
        $periode->jumlah_tka = 10;
        $periode->jumlah_tkj = 10;
        $periode->min_lulus_tka = 5;
        $periode->min_lulus_tkj = 5;
        $periode->is_Active = true;
        $periode->save();

        $jurusan = Jurusan::all();
        foreach ($jurusan as $key => $value) {
            # code...
            $kategori = new KatJurusanPerPeriode;
            $kategori->periode_id = $periode->id;
            $kategori->komposisi_tka = $value->komposisi_tka_default;
            $kategori->komposisi_tkj = $value->komposisi_tkj_default;
            $kategori->jurusan_id = $value->id;
            $kategori->save();
        }
        echo "periode stored \n";
    }
}
