<?php

namespace App\Http\Controllers;

use App\Models\PmPlan;
use App\Models\Device;
use Illuminate\Http\Request;

class PmPlanController extends Controller
{
    /**
     * Display all PM plans
     */
    public function index()
    {
        $plans = PmPlan::with('device')->paginate(10);
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
     * (OPTIONAL) Edit PM Plan - مفيد جداً
     */
    public function edit($id)
    {
        $plan = PmPlan::findOrFail($id);
        $devices = Device::all();

        return view('pm.plans.edit', compact('plan', 'devices'));
    }

    /**
     * (OPTIONAL) Update PM Plan
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
