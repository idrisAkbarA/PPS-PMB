<?php

namespace App\Console\Commands;

use App\JadwalTR;
use App\UserClnMhs;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Faker\Factory as Faker;

class initTemuRamah extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'initTemuRamah';

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
        $data = [];
        $cln_mhs = UserClnMhs::all();
        for ($i = 0; $i < 10; $i++) {
            $kuota = rand(5, 10);
            $faker = Faker::create("id_ID");
            $mhs_ids = [];
            for ($j = 0; $j < rand(1, $kuota); $j++) {

                $index = rand(0, count($cln_mhs) - 1);
                $mhs_id = $cln_mhs[$index]->id;
                array_push($mhs_ids, $mhs_id);
                // echo $mhs_ids[$j] . "\n";
                // unset($cln_mhs[$index]);
            }
            $mhs_ids = json_encode($mhs_ids);
            $data[] = [
                'tanggal' => Carbon::now()->addDays($i),
                'quota' => $kuota,
                'nama_dosen' => $faker->name(),
                "periode_id" => 1,
                "ids_cln_mhs" => $mhs_ids,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ];
        }
        echo $mhs_ids;
        foreach ($data as $temu_ramah) {
            # code...
            JadwalTR::insert($temu_ramah);
        }
    }
}
