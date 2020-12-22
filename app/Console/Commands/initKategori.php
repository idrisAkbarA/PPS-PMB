<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jurusan;
use App\Kategori;

class initKategori extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'initKategori';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'initiaLize default kategori';

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
        $level = ['Mudah', 'Menengah', 'Sulit'];
        $jurusans = Jurusan::All();
        foreach ($jurusans as $key => $value) {
            foreach ($level as $key => $valueLevel) {
                $kategori = new Kategori;
                $kategori->nama = $valueLevel;
                $kategori->deskripsi = 'Soal dengan tingkat kesulitan ' . $valueLevel;
                $kategori->jurusan_id = $value->id;
                $kategori->save();
            }
        }
        echo 'Kategori default stored \n';
    }
}
