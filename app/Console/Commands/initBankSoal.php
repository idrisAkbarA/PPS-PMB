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
        echo "Storing TKA\n";
        for ($i = 0; $i < 100; $i++) {
            self::store('tka');
        }
        echo "Storing TKJ\n";
        for ($i = 0; $i < 100; $i++) {
            self::store('tkj');
        }
    }
    public function storeV2($type)
    {
        $jurusan = Jurusan::orderBy('id', 'DESC')
            ->with('kategori')
            ->get();
        $bankSoal = [];
        foreach ($jurusan as $key => $value) {
            foreach ($value['kategori'] as $keyK => $valueK) {

                // $faker = Faker::create("id_ID");
                // $bankSoal[] = [
                //     'type' => $type,
                //     "jurusan_id" => $value['id'],
                //     "kategori_id" => $valueK['id'],
                //     "pertanyaan" => $faker->sentence(),
                //     "pilihan_ganda" => [
                //         ['pilihan' => 'A', 'text' => $faker->text($maxNbChars = 20)],
                //         ['pilihan' => 'B', 'text' => $faker->text($maxNbChars = 20)],
                //         ['pilihan' => 'C', 'text' => $faker->text($maxNbChars = 20)],
                //         ['pilihan' => 'D', 'text' => $faker->text($maxNbChars = 20)],
                //         ['pilihan' => 'E', 'text' => $faker->text($maxNbChars = 20)]
                //     ],
                //     'jawaban' => 'A',
                //     'created_at' => now()->toDateTimeString(),
                //     'updated_at' => now()->toDateTimeString()
                // ];
                // $bankSoal->jawaban = ['A', 'B', 'C', 'D', 'E'][rand(0, 4)];
                $faker = Faker::create("id_ID");

                $bankSoal['type'] = $type;
                $bankSoal['jurusan_id'] = $value['id'];
                $bankSoal['kategori_id'] = $valueK['id'];
                $bankSoal['pertanyaan'] = $faker->sentence();
                $bankSoal['pilihan_ganda'] = [
                    ['pilihan' => 'A', 'text' => $faker->text($maxNbChars = 20)],
                    ['pilihan' => 'B', 'text' => $faker->text($maxNbChars = 20)],
                    ['pilihan' => 'C', 'text' => $faker->text($maxNbChars = 20)],
                    ['pilihan' => 'D', 'text' => $faker->text($maxNbChars = 20)],
                    ['pilihan' => 'E', 'text' => $faker->text($maxNbChars = 20)]
                ];
                $bankSoal['jawaban'] = 'A';
                BankSoal::insert($bankSoal);
            }
            // $bankSoalChunked = array_chunk();

            // foreach ($bankSoalChunked as $soal) {
            //     BankSoal::insert($soal);
            // }
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
                $bankSoal->pertanyaan = $faker->sentence();
                $bankSoal->pilihan_ganda = [
                    ['pilihan' => 'A', 'text' => $faker->text($maxNbChars = 20)],
                    ['pilihan' => 'B', 'text' => $faker->text($maxNbChars = 20)],
                    ['pilihan' => 'C', 'text' => $faker->text($maxNbChars = 20)],
                    ['pilihan' => 'D', 'text' => $faker->text($maxNbChars = 20)],
                    ['pilihan' => 'E', 'text' => $faker->text($maxNbChars = 20)]
                ];
                $bankSoal->jawaban = 'A';
                // $bankSoal->jawaban = ['A', 'B', 'C', 'D', 'E'][rand(0, 4)];
                $bankSoal->save();
            }
        }
    }
}
