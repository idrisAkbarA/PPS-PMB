<?php

namespace App\Http\Controllers;

use App\Jobs\GitPull;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;

class CIController extends Controller
{
    public function initAllSequence()
    {
        Bus::chain([
            new GitPull()
        ])->dispatch();
    }
    public function pull()
    {
        $pull = new GitPull();
        dispatch($pull);
    }
}
