<?php

namespace App\Http\Controllers;

use App\Http\Requests\MonitorUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\UptimeMonitor\Models\Monitor;

class MonitorController extends Controller
{
    public function edit(Monitor $monitor): View
    {
        return view('monitor.edit', compact(['monitor']));
    }

    public function update(MonitorUpdateRequest $request, Monitor $monitor): RedirectResponse
    {
        $validated = $request->validated();

        $optionalDefaults = [
            'uptime_check_enabled' => 0,
            'certificate_check_enabled' => 0,
            'look_for_string' => '',

        ];

        foreach ($optionalDefaults as $key => $default) {
            $validated[$key] = $validated[$key] ?? $default;
        }

        $monitor->update($validated);

        return back();
    }

    public function destroy(Monitor $monitor): RedirectResponse
    {
        $monitor->delete();

        return redirect(route('dashboard.index'));
    }
}
