<?php

namespace App\Http\Controllers;

use App\Models\PmPlan;
use App\Models\Device;
use Illuminate\Http\Request;
use Carbon\Carbon; // ✅ الاستيراد الصحيح

class PmPlanController extends Controller
{
    /**
     * Display all PM plans
     */
   public function index(Request $request)
{
    $query = PmPlan::with('device');

    // 🔴 Overdue PM
    if ($request->has('overdue')) {
        $query->where('next_pm_date', '<', Carbon::today());
    }

    // 🔵 Due Soon (30 days)
    if ($request->get('due') === 'soon') {
        $query->whereBetween(
            'next_pm_date',
            [Carbon::today(), Carbon::today()->addDays(30)]
        );
    }

    $plans = $query->orderBy('next_pm_date')->paginate(10);

    return view('pm.plans.index', compact('plans'));
}

    /**
     * Show create form
     */
    public function create()
    {
        $devices = Device::all();
        return view('pm.plans.create', compact('devices'));
    }

    /**
     * Store new PM plan
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_id'        => 'required|exists:devices,id',
            'interval_months'  => 'required|integer|min:1',
            'next_pm_date'     => 'required|date',
            'notes'            => 'nullable|string',
        ]);

        PmPlan::create($validated);

        return redirect()
            ->route('pm.plans.index')
            ->with('success', 'PM Plan created successfully');
    }

    /**
     * Show PM plan details
     */
    public function show($id)
    {
        $plan = PmPlan::with(['records', 'device'])->findOrFail($id);
        return view('pm.plans.show', compact('plan'));
    }

    /**
     * Edit PM Plan
     */
    public function edit($id)
    {
        $plan = PmPlan::findOrFail($id);
        $devices = Device::all();

        return view('pm.plans.edit', compact('plan', 'devices'));
    }

    /**
     * Update PM Plan
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'device_id'        => 'required|exists:devices,id',
            'interval_months'  => 'required|integer|min:1',
            'next_pm_date'     => 'required|date',
            'notes'            => 'nullable|string',
        ]);

        $plan = PmPlan::findOrFail($id);
        $plan->update($validated);

        return redirect()
            ->route('pm.plans.index')
            ->with('success', 'PM Plan updated successfully');
    }
}
