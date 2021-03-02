<?php

namespace App\Http\Controllers;

use App\Jobs\GitPull;
use App\Jobs\NPMRunProd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;

class CIController extends Controller
{
    public function initAllSequences()
    {
        Bus::withChain([
            new GitPull(),
            new NPMRunProd(),
        ])->dispatch();
    }
    public function pull()
    {
        $pull = new GitPull();
        dispatch($pull);
    }
    public function NPMRunProd()
    {
        $run_prod = new NPMRunProd();
        dispatch($run_prod);
    }
}
