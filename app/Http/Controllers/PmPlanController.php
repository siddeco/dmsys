<?php

namespace App\Http\Controllers;

use App\Models\PmPlan;
use App\Models\Device;
use App\Models\User;
use App\Models\PmRecord;
use App\Models\Breakdown;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PmPlanController extends Controller
{
    use AuthorizesRequests;

    /* =========================
       INDEX
    ========================= */
    public function index(Request $request)
    {
        $query = PmPlan::with('device');

        if (auth()->user()->hasRole('technician')) {
            $query->where('assigned_to', auth()->id());
        }

        if ($request->get('due') === 'soon') {
            $query->whereDate('next_pm_date', '<=', now()->addDays(30));
        }

        $plans = $query->paginate(10);

        return view('pm.plans.index', compact('plans'));
    }

    /* =========================
       CREATE
    ========================= */
    public function create()
    {
        $devices = Device::all();
        return view('pm.plans.create', compact('devices'));
    }

    /* =========================
       STORE
    ========================= */
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

    /* =========================
       SHOW
    ========================= */
    public function show($id)
    {
        $plan = PmPlan::with(['device', 'records', 'assignedUser'])->findOrFail($id);
        $technicians = User::role('technician')->select('id','name')->get();

        return view('pm.plans.show', compact('plan', 'technicians'));
    }

    /* =========================
       ASSIGN PM
    ========================= */
    public function assign(Request $request, PmPlan $plan)
    {
        $this->authorize('manage pm');

        $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $plan->update([
            'assigned_to' => $request->assigned_to,
            'status'      => 'assigned',
        ]);

        return back()->with('success', 'PM assigned successfully');
    }

    /* =========================
       START PM
    ========================= */
    public function start(PmPlan $plan)
    {
        $this->authorize('work pm');

        abort_if(auth()->id() !== $plan->assigned_to, 403);

        $plan->update([
            'status' => 'in_progress',
        ]);

        return back()->with('success', 'PM work started');
    }

    /* =========================
       COMPLETE PM
    ========================= */
    



public function complete(Request $request, PmPlan $plan)
{
    $this->authorize('work pm');

    abort_if(auth()->id() !== $plan->assigned_to, 403);

    $request->validate([
        'report' => 'required|string',
        'status' => 'required|in:ok,needs_parts,critical',
    ]);

    // إنشاء سجل PM
    PmRecord::create([
        'pm_plan_id'    => $plan->id,
        'device_id'     => $plan->device_id,
        'performed_at'  => now(),
        'engineer_name' => auth()->user()->name,
        'status'        => $request->status,
        'report'        => $request->report,
    ]);

    // 🔔 إذا كانت النتيجة Critical → أنشئ Breakdown
    $projectId = optional($plan->device)->project_id;

    if ($request->status === 'critical' && $projectId) {
        Breakdown::create([
            'device_id'   => $plan->device_id,
            'project_id'  => $projectId,
            'reported_by' => auth()->id(),
            'title'       => 'Critical issue detected during PM',
            'description' =>
                'Critical issue detected during PM.' . PHP_EOL .
                'PM Plan ID: ' . $plan->id . PHP_EOL .
                'Report: ' . $request->report,
            'status'      => 'open',
            'reported_at' => now(),
        ]);
    }

    // تحديث خطة الصيانة
    $plan->update([
        'status'       => 'done',
        'next_pm_date' => now()->addMonths($plan->interval_months),
    ]);

    // ✅ هذا هو السطر الذي كان مفقودًا
    return back()->with('success', 'PM completed successfully');
}


}
