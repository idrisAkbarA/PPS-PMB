<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jurusan;
use App\Kategori;

class initBankSoal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'initBankSoal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'initialize bank soal';

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
        $jurusan = Jurusan::orderBy('id', 'DESC')->with('kategori')
            ->get();
        dd($jurusan);
    }
}
