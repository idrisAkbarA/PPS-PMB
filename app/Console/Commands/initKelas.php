<?php

namespace App\Console\Commands;

use App\Jurusan;
use Illuminate\Console\Command;
use App\Kelas;
use App\Periode;
use App\UserClnMhs;

class initKelas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'initKelas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Init kelas dummy';

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
        $periode = Periode::getActive();
        $clnMhs = UserClnMhs::all();
        $jurusan = Jurusan::all();
        $clnMhss = [];
        // dd($periode);
        foreach ($clnMhs as $key => $value) {
            array_push($clnMhss, ['id' => $value->id, 'nama' => $value->nama]);
        }
        foreach ($jurusan as $key => $value) {

            $kelas = Kelas::create([
                'periode_id' => $periode->id,
                'jurusan_id' => $value->id,
                'cln_mhs' => $clnMhss,
            ]);
        }
        echo "Kelas stored \n";
    }
}
