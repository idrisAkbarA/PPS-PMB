<?php

namespace App\Console\Commands;

use App\BankSoal;
use App\Jurusan;
use App\Kategori;
use App\KatJurusanPerPeriode;
use Illuminate\Console\Command;

class convertAllSoalKomposisi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convertAllSoalKomposisi { periode_id : id periode }';

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
        $periode_id = $this->argument('periode_id');
        // $kategori_id = $this->argument('kategori_id');
        $komposisi = KatJurusanPerPeriode::where('periode_id', $periode_id)->get();
        $bankSoal = BankSoal::all();
        $jurusan = Jurusan::all();

        $records = 0;
        echo "changing all soals records to kategori mudah...\n";
        foreach ($bankSoal as $key => $value) {
            $records++;
            $kategori = Kategori::where([
                'jurusan_id' => $value['jurusan_id'],
                'nama' => 'Mudah',
            ])->first();
            if ($value['kategori_id'] == $kategori->id) {
                echo "id " . $value['id'] . " already mudah \n";
                continue;
            };
            $update_bank_soal = BankSoal::find($value['id']);
            $update_bank_soal->kategori_id = $kategori->id;
            $update_bank_soal->save();
            echo "id " . $value['id'] . " changed \n";
        }
        echo $records . "\n";

        echo 'changing all kategori per jurusan per periode to komposisi mudah';

        foreach ($komposisi as $key => $value) {
            $kategori = Kategori::where([
                'jurusan_id' => $value['jurusan_id'],
                'nama' => 'Mudah',
            ])->first();
            $update_komposisi = KatJurusanPerPeriode::find($value['id']);
            $komposisi_baru = [
                [
                    'jumlah' => 70,
                    'kategori_id' => $kategori->id,
                    'nama_kategori' => $kategori->nama
                ]
            ];
            $update_komposisi->komposisi_tka = $komposisi_baru;
            $update_komposisi->komposisi_tkj = $komposisi_baru;
            $update_komposisi->save();
            echo "komposisi updated \n";
        }
    }
}
