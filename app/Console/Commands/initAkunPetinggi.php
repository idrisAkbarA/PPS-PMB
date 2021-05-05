<?php

namespace App\Console\Commands;

use App\UserPetugas;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class initAkunPetinggi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'initAkunPetinggi';

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
        UserPetugas::create([
            'username' => "usman",
            'nama'  => "Idris Akbar Adyusman",
            'role'  => 3,
            'email' => 'test@test.com',
            'password' => Hash::make('123')
        ]);
        echo "Petinggi Created";
    }
}
