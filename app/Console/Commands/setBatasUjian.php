<?php

namespace App\Console\Commands;

use App\Library\SoalUjian;
use App\Ujian;
use Illuminate\Console\Command;

class setBatasUjian extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setBatasUjian';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $ujian = Ujian::with('periode')
            ->whereNotNull('lunas_at')
            ->whereNull('batas_ujian')
            ->get();
        // dd($ujian);
        foreach ($ujian as $key => $value) {
            $ujian = Ujian::with('periode')->find($value['id']);
            $soalUjian = new SoalUjian;
            $batas_ujian = $soalUjian->calcDeadline($ujian->id, $ujian->lunas_at);
            $ujian->batas_ujian = $batas_ujian;
            $ujian->save();
        }
    }
}
