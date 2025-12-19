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
    public function index(Request $request)
    {
        abort_unless(auth()->user()->can('manage pm'), 403);

        $query = PmRecord::with([
            'device',
            'pmPlan',
        ])->latest('performed_at');

        /* =========================
           SEARCH (Device / SN / Engineer)
        ========================= */
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('engineer_name', 'like', '%' . $request->q . '%')
                    ->orWhereHas('device', function ($d) use ($request) {
                        $d->where('name', 'like', '%' . $request->q . '%')
                            ->orWhere('serial_number', 'like', '%' . $request->q . '%');
                    });
            });
        }

        /* =========================
           RESULT FILTER
        ========================= */
        if ($request->filled('result')) {
            $query->where('status', $request->result);
        }

        $records = $query
            ->paginate(15)
            ->withQueryString();

        return view('pm.records.index', compact('records'));
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
            'status' => 'required|in:ok,needs_parts,critical',
            'report' => 'nullable|string',
        ]);

        // Get logged-in user's name
        $engineer = auth()->user()->name;

        // Fetch plan
        $plan = PmPlan::findOrFail($plan_id);

        // Create record
        PmRecord::create([
            'pm_plan_id' => $plan->id,
            'device_id' => $plan->device_id,
            'engineer_name' => $engineer,  // << auto-fill engineer name
            'performed_at' => $validated['performed_at'],
            'status' => $validated['status'],
            'report' => $validated['report'] ?? null,
        ]);

        // Update next PM date
        $next_date = date('Y-m-d', strtotime("+{$plan->interval_months} months", strtotime($validated['performed_at'])));
        $plan->update(['next_pm_date' => $next_date]);

        return redirect()
            ->route('pm.plans.show', $plan_id)
            ->with('success', 'PM Record created successfully.');
    }

    public function show(PmRecord $record)
    {
        abort_unless(auth()->user()->can('manage pm'), 403);

        $record->load([
            'device.project',
            'pmPlan',
            'breakdown', // ⭐
        ]);


        return view('pm.records.show', compact('record'));
    }
}
