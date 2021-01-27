<?php

namespace App\Console\Commands;
use App\BankSoal;
use Illuminate\Console\Command;

class setJawaban extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setJawaban';

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
        $bank_soal = BankSoal::where(["jurusan_id"=>1])->get();
        foreach ($bank_soal as $key => $value) {
            $nb = BankSoal::find($value["id"]);
            $nb->jawaban = "A";
            $nb->save();
        }
    }
}
