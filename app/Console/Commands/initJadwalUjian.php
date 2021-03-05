<?php

namespace App\Console\Commands;

use App\Periode;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Date;

class initJadwalUjian extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'initJadwalUjian';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'initialize jadwal ujian for testing';

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
        $today = Date('Y-m-d H:i:s');
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $today)->format('Y-m-d H:i:s');
        $jadwals = [
            [
                'start' => $date,
                'end' => Carbon::createFromFormat('Y-m-d H:i:s', $today)->addDays(5)->format('Y-m-d H:i:s')
            ],
            [
                'start' => Carbon::createFromFormat('Y-m-d H:i:s', $today)->addDays(10)->format('Y-m-d H:i:s'),
                'end' => Carbon::createFromFormat('Y-m-d H:i:s', $today)->addDays(15)->format('Y-m-d H:i:s')
            ],
        ];
        $periode = Periode::latest()->first();
        $periode->jadwal_ujian = $jadwals;
        $periode->save();
        echo "jadwal stored \n";
    }
}
