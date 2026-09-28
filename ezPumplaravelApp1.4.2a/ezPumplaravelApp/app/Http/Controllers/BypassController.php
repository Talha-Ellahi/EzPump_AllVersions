<?php

namespace App\Http\Controllers;

use App\Models\BypassLimit;
use App\Models\BypassRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class BypassController extends Controller
{

    public function showBypassLimits()
    {
        $limits = BypassLimit::with('records')->paginate(10);
        return view('bypass.bypass_limit', compact('limits'));
    }
    public function store(Request $request)
    {

        $validated = $request->validate([
            'sys_id' => 'required|string',
            'month' => 'required',
            'total_limit' => 'required|integer|min:1'
        ]);

        $limit = BypassLimit::create($validated);

        // Initialize BypassRecord


        return redirect()->route('bypass.limit.index')->with('success', 'Bypass Limit Added');
    }
    public function edit($id)
    {
        $editLimit = BypassLimit::findOrFail($id);
        $limits = BypassLimit::with('records')->paginate(10);
        return view('bypass.bypass_limit', compact('limits', 'editLimit'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'sys_id' => 'required',
            'month' => 'required|date_format:Y-m',
            'total_limit' => 'required|integer|min:0',
        ]);

        $limit = BypassLimit::findOrFail($id);

        // Sum of used_limit from related records
        $usedLimit = BypassRecord::where('bypass_limit_id', $limit->id)->sum('used_limit');

        // Validation: New total_limit must not be less than already used limit
        if ($request->total_limit < $usedLimit) {
            return redirect()->route('bypass.limit.index')->with('error', 'Cannot set Total Limit less than already used limit (' . $usedLimit . ').');
        }

        // Passed Validation → Update
        $limit->update($request->only(['sys_id', 'month', 'total_limit']));
        $lastRecord = BypassRecord::where('bypass_limit_id', $limit->id)->latest()->first();

        if ($lastRecord) {
            $lastRecord->remaining_limit = $request->total_limit - $usedLimit;
            $lastRecord->save();
        }

        return redirect()->route('bypass.limit.index')->with('success', 'Bypass limit updated successfully.');
    }
    public function destroy($id)
    {
        $limit = BypassLimit::findOrFail($id);
        $limit->delete();
        return redirect()->route('bypass.limit.index')->with('success', 'Bypass limit deleted successfully.');
    }
    public function unlock(Request $request)
    {

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'add_hours' => 'required|integer|min:1',
        ]);
        $month=Carbon::now()->format('Y-m');
        $limit = BypassLimit::where('month',$month)->latest()->first();

        if (!$limit) {
            return back()->with('error', 'No bypass limit found.');
        }

        $lastRecord = BypassRecord::where('bypass_limit_id', $limit->id)->latest()->first();

        if ($lastRecord && $lastRecord->remaining_limit <= 0) {
            return back()->with('error', 'No remaining bypass limit.');
        }
        $newRemainingLimit = $lastRecord
            ? $lastRecord->remaining_limit - 1
            : $limit->total_limit - 1; // First time bypass use (start from total_limit)

// Prevent negative remaining limit
        if ($newRemainingLimit < 0) {
            return back()->with('error', 'No remaining bypass limit.');
        }
        // Create Bypass Record Entry
        BypassRecord::create([
            'bypass_limit_id' => $limit->id,
            'used_limit' => 1,
            'remaining_limit' => $newRemainingLimit,
            'add_hours' => $request->add_hours,
        ]);
        Cache::forget('bypass_active_until');
        Cache::forget('system_locked');
        Cache::forget('shift_minutes_remaining');

        // Unlock logic here (e.g., update user status, send websocket event, etc.)

        return back()->with('success', 'Screen lock bypassed for ' . $request->add_hours . ' hour(s).');
    }

}
