<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Ujian;

class getLastKodeBayar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getKodeBayar {jurusanID} {--all}';

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
        $jurusan_id = $this->argument('jurusanID');
        $isAll = $this->option("all");
        if ($isAll) {
            $kodeBayar = Ujian::where('jurusan_id', $jurusan_id)->whereNotNull('kode_bayar')->get();
            foreach ($kodeBayar as $key => $value) {
                echo $value->kode_bayar;
                echo "\n";
            }
            return 0;
        }
        $kodeBayar = Ujian::where('jurusan_id', $jurusan_id)->whereNotNull('kode_bayar')->latest()->first();
        // echo $this->option('all');
        echo $kodeBayar->kode_bayar;
        return 0;
    }
}
