<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ExportSummary;
use App\Models\Approval;
use App\Models\Vehicle;

class SummaryController extends Controller
{
    public function index()
    {
        $approval = Approval::with('vehicle')->orderBy('entry_time', 'ASC')->get();
        $vehicle = Vehicle::with('user')->get();

        return view('admin.summary.index', compact([
            'approval',
            'vehicle',
        ]));
    }

    public function export(){
        return (new ExportSummary)->download('DataPengunjung.xlsx');
    }
}
