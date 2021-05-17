<?php

namespace App\Console\Commands;

use App\Ujian;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MassSetLulusCumlaude extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'massSetLulusCumlaude';

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
        echo "Setting new value... \n";

        $ujians = Ujian::where([
            'is_jalur_cumlaude' => 1,
            'is_lulus_tka' => 1,
            'is_lulus_tkj' => 1,
            'is_lunas' => 1
        ])
            ->whereNull('lulus_at')
            ->get();
        foreach ($ujians as $key => $value) {
            $ujian = Ujian::find($value->id);
            $ujian->lulus_at = Carbon::now();
            $ujian->save();
        }

        // Ujian::where([
        //     'is_jalur_cumlaude' => 1,
        //     'is_lulus_tka' => 1,
        //     'is_lulus_tkj' => 1,
        // ])
        //     ->whereNotNull('lulus_at')
        //     ->update(['lulus_at' => Carbon::now()]);


        echo count($ujians) . " records changed.. Done!\n";
    }
}
