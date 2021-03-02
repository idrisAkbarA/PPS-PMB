<?php

namespace App\Http\Controllers;

use App\Jobs\ComposerInstall;
use App\Jobs\CustomProccess;
use App\Jobs\GitPull;
use App\Jobs\MigrateDB;
use App\Jobs\NPMInstall;
use App\Jobs\NPMRunProd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;

class CIController extends Controller
{
    public function initAllSequences()
    {
        GitPull::withChain([
            new ComposerInstall(),
            new NPMInstall(),
            new NPMRunProd(),
            new CustomProccess('php artisan migrate')
            // new MigrateDB(),
        ])->dispatch();
    }
    public function frontEnd($isNewPkg = false)
    {
        ($isNewPkg) ?
            GitPull::withChain([
                new NPMInstall(),
                new NPMRunProd(),
            ])->dispatch()
            :
            GitPull::withChain([
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
    public function custom(Request $request)
    {
        $proc = new CustomProccess($request->args);
        dispatch($proc);
    }
}
