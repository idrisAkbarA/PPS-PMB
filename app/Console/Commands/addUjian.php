<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\Periode;
use App\Jurusan;
use App\Ujian;
use App\UserClnMhs;

class addUjian extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'addUjian {userID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'add ujian';

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
        $jurusan = Jurusan::find(1);
        $periode = new Periode;
        $periode_active = $periode->where('is_active', true)->first();
        $rangeUjian = $periode_active->range_ujian;
        $batasUjian = Carbon::now()->addDays($rangeUjian);
        $ujian = new Ujian;
        $ujian->periode_id = $periode_active['id'];
        $ujian->jurusan_id = 1;
        $ujian->user_cln_mhs_id = $this->argument('userID');
        $ujian->komposisi_tka = $jurusan->komposisi_tka_default;
        $ujian->komposisi_tkj = $jurusan->komposisi_tkj_default;
        $ujian->lunas_at = Carbon::now();
        $ujian->batas_ujian = $batasUjian;
        $ujian->save();
    }
}
