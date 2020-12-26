<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\UserClnMhs;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class initClnMhs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'initClnMhs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize cln mhs';

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
        $user = new UserClnMhs;
        $user->nama = "Idris Akbar Adyusman";
        $user->email = "asd@asd.asd";
        $user->password = Hash::make("123");
        $user->save();
        for ($i = 0; $i < 10; $i++) {
            $faker = Faker::create("id_ID");
            $user = new UserClnMhs;
            $user->nama = $faker->name();
            $user->email = $faker->email();
            $user->password = Hash::make("123");
            $user->save();
        }
        echo "user testing created\n";
    }
}
