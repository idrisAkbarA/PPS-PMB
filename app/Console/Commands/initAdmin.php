<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\UserPetugas;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
class initAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'initAdmin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'initialize admin Account';

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
        $user = new UserPetugas;
        $user->username = "admin";
        $user->nama = "Idris Akbar Adyusman";
        $user->email = "asd@asd.asd";
        $user->role = 1;
        $user->password = Hash::make("admin123");
        $user->save();
        echo "user admin created\n";
    }
}
