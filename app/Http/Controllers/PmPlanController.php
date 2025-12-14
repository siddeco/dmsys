<?php

namespace App\Http\Controllers;

use App\Models\PmPlan;
use App\Models\Device;
use App\Models\User;
use App\Models\PmRecord;
use App\Models\Breakdown;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PmPlanController extends Controller
{
    use AuthorizesRequests;

    /* =========================
       INDEX
    ========================= */
    public function index(Request $request)
    {
        $query = PmPlan::with(['device', 'assignedUser']);

        if (auth()->user()->hasRole('technician')) {
            $query->where('assigned_to', auth()->id());
        }

        // 🔹 PM Due Soon (30 days)
    if ($request->get('filter') === 'due_soon') {
        $query->whereNotNull('next_pm_date')
              ->where('status', '!=', 'done')
              ->whereBetween('next_pm_date', [
                  now()->startOfDay(),
                  now()->addDays(30)->endOfDay()
              ]);
    }

        // 🔹 PM Overdue
    // 🔥 PM Overdue
    if ($request->get('overdue')) {
        $query->whereDate('next_pm_date', '<', now())
              ->where('status', '!=', 'done');
    }

 



        $plans = $query
        ->orderBy('next_pm_date')
        ->paginate(10)
        ->withQueryString();

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
    abort_unless(auth()->user()->can('work pm'), 403);
    abort_unless(auth()->id() === $plan->assigned_to, 403);

    $request->validate([
        'result' => 'required|in:ok,needs_parts,critical',
        'report' => 'nullable|string',
        'report_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
        'scan_image' => 'nullable|string',
    ]);

    // 🔴 شرط إلزامي: ملف أو Scan
    if (!$request->hasFile('report_file') && !$request->scan_image) {
        return back()->withErrors([
            'report_file' => 'Service report file or scan is required.'
        ]);
    }

    // 📂 حفظ الملف إن وُجد
    $filePath = null;
    if ($request->hasFile('report_file')) {
        $filePath = $request->file('report_file')
            ->store('pm_reports', 'public');
    }

    // 📝 إنشاء سجل PM
    $record = PmRecord::create([
        'pm_plan_id'   => $plan->id,
        'device_id'    => $plan->device_id,
        'performed_at' => now(),
        'engineer_name'=> auth()->user()->name,
        'status'       => $request->result,
        'report'       => $request->report,
        'report_file'  => $filePath,
        'scan_image'   => $request->scan_image,
    ]);

    // 🔔 CRITICAL → إنشاء Breakdown تلقائي
    if ($request->result === 'critical' && $plan->device?->project_id) {
        Breakdown::create([
            'device_id'   => $plan->device_id,
            'project_id'  => $plan->device->project_id,
            'reported_by' => auth()->id(),
            'title'       => 'Critical issue detected during PM',
            'description' =>
                'Critical issue detected during PM.' . PHP_EOL .
                'PM Plan ID: ' . $plan->id,
            'status'      => 'open',
            'reported_at' => now(),
        ]);
    }

    // ⏭ تحديث الخطة
    $plan->update([
        'status' => 'done',
        'next_pm_date' => now()->addMonths($plan->interval_months),
    ]);

    return redirect()
        ->route('pm.plans.show', $plan)
        ->with('success', 'PM completed successfully');
}


}
