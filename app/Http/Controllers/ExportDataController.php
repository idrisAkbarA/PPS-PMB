<?php

namespace App\Http\Controllers;

use App\Exports\UserWhoPassedExamExport;
use App\Library\ExportData;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportDataController extends Controller
{
    public function index($periode_id)
    {
        // return (ExportData::collection($periode_id))->downdloadExcel($headings = true);
        return Excel::download(new UserWhoPassedExamExport($periode_id), 'users.xlsx');
    }
}
