<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\UptimeMonitor\Models\Monitor;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $sortBy = $request->get('sort', 'url');
        $sortDirection = $request->get('direction', 'asc');

        $monitors = Monitor::orderBy($sortBy, $sortDirection)->get();

        return view('dashboard', compact(['monitors', 'sortBy', 'sortDirection']));

    }
}
