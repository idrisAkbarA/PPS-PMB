<?php

namespace App\Console\Commands;

use App\Http\Controllers\KelasController;
use Illuminate\Console\Command;
use App\Library\SoalUjian;

class sandBox extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sandBox';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'method for testing purpose';

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
        // $soal = SoalUjian::getSoal("tkj", 1);
        $kelas = new KelasController;
        return $kelas->addNewStudent(1, 1, 1);
    }
}
