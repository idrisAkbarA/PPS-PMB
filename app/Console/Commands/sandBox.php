<?php

namespace App\Console\Commands;

use App\Http\Controllers\KelasController;
use Illuminate\Console\Command;
use App\Library\SoalUjian;
use App\Periode;

class sandBox extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sandBox';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'method for testing purpose';

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
        // $soal = SoalUjian::getSoal("tkj", 1);
        $name = "akbar";
        $periode = periode::orderBy('id', 'DESC')->with("ujian")->get();
        $count = 0;
        $result = $periode;
        $isSearched = false;
        $isName = false;
        if ($name) {
            $isName = true;
            $result = $periode->toArray();
            foreach ($result as $key => $value) {
                $temp = [];
                foreach ($value['ujian'] as $k => $ujian) {
                    // echo $ujian['nama_pendaftar'];
                    $isFound = stripos($ujian['nama_pendaftar'], $name);
                    echo $name . " | " . $k . " "  . " | " . (gettype($isFound) == 'integer' ? "found" : "-----") . " | " . $ujian['nama_pendaftar']  . "\n";
                    if (gettype($isFound) == 'integer') {
                        $count++;
                        array_push($temp, $ujian);
                    }
                }
                $result[$key]['ujian'] = $temp;
            }
        }
        echo $count . "\n";
        foreach ($result as $key => $value) {
            foreach ($value['ujian'] as $k => $ujian) {
                // echo $ujian['nama_pendaftar'];
                // $isFound = stripos($ujian['nama_pendaftar'], $name);
                echo $ujian['nama_pendaftar']  . "\n";
            }
            echo count($value['ujian']);
        }
        // return response()->json($name);
        // return $name;
        // return gettype($periode[0]['ujian']);
        // return (array)$periode[0]['ujian'] = ['pantek'];
        // return response()->json($result);
        // return response()->json([$result, $isSearched, $isName]);
    }
}
