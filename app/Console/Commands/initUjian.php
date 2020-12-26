<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Periode;
use App\Jurusan;
use App\Ujian;
use App\UserClnMhs;
use Illuminate\Support\Carbon;

class initUjian extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'initUjian';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'initialize Ujian';

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
        $cln_mhss = UserClnMhs::all();
        foreach ($cln_mhss as $key => $value) {
            $jurusans = Jurusan::all();
            $jurusan = $jurusans[rand(0, count($jurusans) - 1)];
            $periode = new Periode;
            $periode_active = $periode->where('is_active', true)->first();
            $rangeUjian = $periode_active->range_ujian;
            $batasUjian = Carbon::now()->addDays($rangeUjian);
            $ujian = new Ujian;
            $ujian->periode_id = $periode_active['id'];
            $ujian->jurusan_id = $jurusan->id;
            $ujian->user_cln_mhs_id = $value->id;
            $ujian->kat_tka_id = $jurusan->kat_tka_default;
            $ujian->kat_tkj_id = $jurusan->kat_tkj_default;
            $ujian->lunas_at = Carbon::now();
            $ujian->batas_ujian = $batasUjian;
            $ujian->save();
        }
        echo "Ujian stored\n";
    }
}
