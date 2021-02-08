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
            "Pendidikan Agama Islam S3",
            "Hukum Keluarga S3",
            "Pendidikan Agama Islam S2",
            "Manajemen Pendidikan Islam S2",
            "Ekonomi Syariah S2",
            "Hukum Keluarga S2",
            "Studi Bahasa Arab S2",
            "Konsentrasi Bahasa Inggris",
            "Konsentrasi Tafsir Hadist"
        ];
        foreach ($program as $key => $value) {
            $jurusan = new Jurusan;
            $jurusan->nama = $value;
            $jurusan->kuota_kelas_default = 10;
            $jurusan->save();
        }
        echo "Jurusan Stored\n";
    }
}
