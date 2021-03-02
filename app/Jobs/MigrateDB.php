<?php

namespace App\Jobs;

use Illuminate\Console\Command;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Process;

class MigrateDB extends Command implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $isFresh;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(bool $isFresh = null)
    {
        $this->isFresh = $isFresh;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->isFresh) {
            $this->call('migrate:fresh');
        } else {
            $this->call('migrate');
        }
    }
}
