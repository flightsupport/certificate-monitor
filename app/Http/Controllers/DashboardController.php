<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Spatie\UptimeMonitor\Models\Monitor;

class DashboardController extends Controller
{
    public function index(): View
    {
        $monitors = Monitor::all();

        return view('dashboard', compact(['monitors']));

    }
}
