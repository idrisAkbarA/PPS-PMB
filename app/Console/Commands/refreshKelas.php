<?php

namespace App\Console\Commands;

use App\Http\Controllers\KelasController;
use Illuminate\Console\Command;
use App\Kelas;
use App\Ujian;

class refreshKelas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refreshKelas { periode_id : id periode }';

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
        Kelas::where('periode_id', $this->argument('periode_id'))->update(['cln_mhs' => "[]"]);
        $ujians = Ujian::where([
            'is_lulus_tka' => 1,
            'is_lulus_tkj' => 1,
            'is_lunas' => 1
        ])
            ->whereNotNull('lulus_at')
            ->get();
        foreach ($ujians as $key => $value) {
            $kelas = new KelasController;
            $kelas->addNewStudent($value->periode_id, $value->jurusan_id, $value->user_cln_mhs_id);
        }
    }
}
