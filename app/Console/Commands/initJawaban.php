<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Library\SoalUjian;
use App\Soal;

class initJawaban extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'initJawaban';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'initialize Random answer';

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
        $jawabans = ['A', 'B', 'C', 'D', 'E'];
        $soal = Soal::all();
        // return $soal;
        foreach ($soal as $key => $value) {
            $idSoal = $value->id;
            foreach ($value->set_pertanyaan[0]->soal as $keyS => $valueS) {
                $idPertanyaan = $valueS->id;
                $soalLib = new SoalUjian;
                $jawaban = $jawabans[rand(0, count($jawabans) - 1)];
                $soalLib->setJawaban('tka', $idSoal, $idPertanyaan, $jawaban);
            }
            foreach ($value->set_pertanyaan[1]->soal as $keyS => $valueS) {
                $idPertanyaan = $valueS->id;
                $soalLib = new SoalUjian;
                $jawaban = $jawabans[rand(0, count($jawabans) - 1)];
                $soalLib->setJawaban('tkj', $idSoal, $idPertanyaan, $jawaban);
            }
        }
        echo "Jawaban Stored\n";
    }
}
