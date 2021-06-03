<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jurusan;

class initJurusan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'initJurusan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'initialize jurusan';

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
        $program = [
            "Pendidikan Agama Islam S3"     => 86008,
            "Hukum Keluarga S3"             => 74030,
            "Pendidikan Agama Islam S2"     => 86108,
            "Manajemen Pendidikan Islam S2" => 86110,
            "Ekonomi Syariah S2"            => 60102,
            "Hukum Keluarga S2"             => 74130,
            "Studi Bahasa Arab S2"          => 86111,
            "Konsentrasi Bahasa Inggris"    => 86108,
            "Konsentrasi Tafsir Hadist"     => 74130
        ];
        foreach ($program as $key => $value) {
            $jurusan = new Jurusan;
            $jurusan->nama = $key;
            $jurusan->kode_jurusan = $value;
            $jurusan->kuota_kelas_default = 10;
            $jurusan->nominal_bayar_default = 400000;
            $jurusan->save();
        }
        echo "Jurusan Stored\n";
    }
}
