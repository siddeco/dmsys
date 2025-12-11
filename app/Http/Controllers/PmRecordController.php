<?php

namespace App\Http\Controllers;

use App\Models\PmRecord;
use App\Models\PmPlan;
use Illuminate\Http\Request;

class PmRecordController extends Controller
{
    /**
     * Show all records for a plan
     */
    public function index($plan_id)
    {
        $plan = PmPlan::with('device')->findOrFail($plan_id);
        $records = PmRecord::where('pm_plan_id', $plan_id)->paginate(10);

        return view('pm.records.index', compact('plan', 'records'));
    }

    /**
     * Show create form
     */
    public function create($plan_id)
    {
        $plan = PmPlan::with('device')->findOrFail($plan_id);
        return view('pm.records.create', compact('plan'));
    }

    /**
     * Store PM record
     */
    public function store(Request $request, $plan_id)
    {
        // Validate fields (engineer_name removed)
        $validated = $request->validate([
            'performed_at' => 'required|date',
            'status'       => 'required|in:ok,needs_parts,critical',
            'report'       => 'nullable|string',
        ]);

        // Get logged-in user's name
        $engineer = auth()->user()->name;

        // Fetch plan
        $plan = PmPlan::findOrFail($plan_id);

        // Create record
        PmRecord::create([
            'pm_plan_id'   => $plan->id,
            'device_id'    => $plan->device_id,
            'engineer_name'=> $engineer,  // << auto-fill engineer name
            'performed_at' => $validated['performed_at'],
            'status'       => $validated['status'],
            'report'       => $validated['report'] ?? null,
        ]);

        // Update next PM date
        $next_date = date('Y-m-d', strtotime("+{$plan->interval_months} months", strtotime($validated['performed_at'])));
        $plan->update(['next_pm_date' => $next_date]);

        return redirect()
            ->route('pm.plans.show', $plan_id)
            ->with('success', 'PM Record created successfully.');
    }
}
