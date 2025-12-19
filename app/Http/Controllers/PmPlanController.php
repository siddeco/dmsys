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
        $query = PmPlan::query()
            ->with([
                'device:id,name,serial_number',
                'assignedUser:id,name',
                // 🔥 تحميل آخر PM فقط
                'records' => function ($q) {
                    $q->latest('performed_at')->limit(1);
                }
            ]);

        /* =========================
           TECHNICIAN SCOPE
        ========================= */
        if (auth()->user()->hasRole('technician')) {
            $query->where('assigned_to', auth()->id());
        }

        /* =========================
           SEARCH (Device name / SN)
        ========================= */
        if ($request->filled('q')) {
            $search = $request->q;

            $query->whereHas('device', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        /* =========================
           PLAN STATUS FILTER
           (new / assigned / in_progress / done)
        ========================= */
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        /* =========================
           TIMING FILTER
           Due Soon / Overdue (OR logic)
        ========================= */
        $dueSoon = $request->boolean('due_soon');
        $overdue = $request->boolean('overdue');

        if ($dueSoon || $overdue) {
            $query->where(function ($q) use ($dueSoon, $overdue) {

                // 🔴 Overdue
                if ($overdue) {
                    $q->orWhere(function ($x) {
                        $x->whereNotNull('next_pm_date')
                            ->whereDate('next_pm_date', '<', now())
                            ->where('status', '!=', 'done');
                    });
                }

                // 🟡 Due Soon (exclude overdue)
                if ($dueSoon) {
                    $q->orWhere(function ($x) {
                        $x->whereNotNull('next_pm_date')
                            ->whereBetween('next_pm_date', [
                                now()->startOfDay(),
                                now()->addDays(30)->endOfDay()
                            ])
                            ->where('status', '!=', 'done');
                    });
                }

            });
        }

        /* =========================
           ASSIGNED TECHNICIAN (ADMIN)
        ========================= */
        if (
            $request->filled('assigned_to') &&
            auth()->user()->can('manage pm')
        ) {
            $query->where('assigned_to', $request->assigned_to);
        }

        /* =========================
           ORDER & PAGINATION
        ========================= */
        $plans = $query
            ->orderByRaw('next_pm_date IS NULL') // nulls last
            ->orderBy('next_pm_date')
            ->paginate(10)
            ->withQueryString();

        /* =========================
           TECHNICIANS LIST (FILTER)
        ========================= */
        $technicians = User::role('technician')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('pm.plans.index', compact('plans', 'technicians'));
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
            'device_id' => 'required|exists:devices,id',
            'interval_months' => 'required|integer|min:1',
            'next_pm_date' => 'required|date',
            'notes' => 'nullable|string',
            'status' => 'pending',
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
        $technicians = User::role('technician')->select('id', 'name')->get();

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
            'status' => 'assigned',
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
            'pm_plan_id' => $plan->id,
            'device_id' => $plan->device_id,
            'performed_at' => now(),
            'engineer_name' => auth()->user()->name,
            'status' => $request->result,
            'report' => $request->report,
            'report_file' => $filePath,
            'scan_image' => $request->scan_image,
        ]);

        // 🔔 CRITICAL → إنشاء Breakdown تلقائي
        if ($request->result === 'critical' && $plan->device?->project_id) {

            Breakdown::create([
                'pm_record_id' => $record->id, // ⭐ الربط المهم
                'device_id' => $plan->device_id,
                'project_id' => $plan->device->project_id,
                'reported_by' => auth()->id(),
                'title' => 'Critical issue detected during PM',
                'description' =>
                    'Critical issue detected during preventive maintenance.' . PHP_EOL .
                    'PM Record ID: ' . $record->id,
                'status' => 'open',
                'reported_at' => now(),
            ]);
        }


        // ⏭ تحديث الخطة
        $plan->update([
            'status' => 'completed',
            'next_pm_date' => now()->addMonths($plan->interval_months),
        ]);

        return redirect()
            ->route('pm.plans.show', $plan)
            ->with('success', 'PM completed successfully');
    }

    /* =========================
  EDIT
========================= */
    public function edit(PmPlan $plan)
    {
        abort_unless(auth()->user()->can('manage pm'), 403);

        if ($plan->status === 'done') {
            return redirect()
                ->route('pm.plans.show', $plan)
                ->with('error', 'Completed PM plans cannot be edited.');
        }

        return view('pm.plans.edit', compact('plan'));
    }

    /* =========================
       UPDATE
    ========================= */
    public function update(Request $request, PmPlan $plan)
    {
        abort_unless(auth()->user()->can('manage pm'), 403);

        if ($plan->status === 'done') {
            return back()->with('error', 'Completed PM plans cannot be edited.');
        }

        $validated = $request->validate([
            'interval_months' => 'required|integer|min:1',
            'next_pm_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $plan->update($validated);

        return redirect()
            ->route('pm.plans.show', $plan)
            ->with('success', 'PM Plan updated successfully.');
    }


    public function bulk(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'action' => 'required|string',
        ]);

        $plans = PmPlan::whereIn('id', $request->ids);

        if ($request->action === 'assign') {
            $request->validate([
                'assigned_to' => 'required|exists:users,id',
            ]);

            $plans->update([
                'assigned_to' => $request->assigned_to,
                'status' => 'assigned',
            ]);
        }

        if ($request->action === 'mark_done') {
            $plans->update([
                'status' => 'done',
            ]);
        }

        if ($request->action === 'delete') {
            $plans->delete();
        }

        return back()->with('success', 'Bulk action applied successfully.');
    }





}
