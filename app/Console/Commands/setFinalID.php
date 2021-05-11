<?php

namespace App\Console\Commands;

use App\Library\SoalUjian;
use App\Ujian;
use Illuminate\Console\Command;

class setFinalID extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setFinalID';

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
        echo ("Setting final IDs..\n");
        $ujians = Ujian::whereNotNull('kode_bayar')->get();
        foreach ($ujians as $key => $value) {
            $soalUjian = new SoalUjian;
            $soalUjian->setFinalID($value->id);
        }
        echo (count($ujians) . " ujian instances set.\n");
    }
}
