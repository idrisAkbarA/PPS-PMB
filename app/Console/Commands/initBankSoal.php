<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jurusan;
use App\Kategori;
use App\BankSoal;
use Faker\Factory as Faker;

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
        echo "Storing TKA";
        for ($i = 0; $i < 20; $i++) {
            self::store('tka');
        }
        echo "Storing TKD";
        for ($i = 0; $i < 20; $i++) {
            self::store('tkd');
        }
    }
    public function store($type)
    {
        $jurusan = Jurusan::orderBy('id', 'DESC')->with('kategori')
            ->get();
        foreach ($jurusan as $key => $value) {
            foreach ($value['kategori'] as $keyK => $valueK) {

                $faker = Faker::create("id_ID");
                $bankSoal = new BankSoal;
                $bankSoal->type = $type;
                $bankSoal->jurusan_id = $value['id'];
                $bankSoal->kategori_id = $valueK['id'];
                $bankSoal->pertanyaan = $faker->text($maxNbChars = 20);
                $bankSoal->pilihan_ganda = json_encode([
                    ['pilihan' => 'A', 'text' => $faker->sentence()],
                    ['pilihan' => 'B', 'text' => $faker->sentence()],
                    ['pilihan' => 'C', 'text' => $faker->sentence()],
                    ['pilihan' => 'D', 'text' => $faker->sentence()],
                    ['pilihan' => 'E', 'text' => $faker->sentence()]
                ]);
                $bankSoal->jawaban = ['A', 'B', 'C', 'D', 'E'][rand(0, 4)];
                $bankSoal->save();
            }
        }
    }
}
