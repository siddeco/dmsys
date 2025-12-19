<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Breakdown;
use App\Models\Device;
use App\Models\Project;
use App\Models\SparePart;
use App\Models\SparePartUsage;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;


class BreakdownController extends Controller
{

    use AuthorizesRequests;
    /**
     * عرض كل بلاغات الأعطال
     */
    public function index(Request $request)
    {
        $query = Breakdown::with([
            'device',
            'project',
            'assignedUser'
        ])->latest();

        /* ================= SEARCH ================= */
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('device', function ($d) use ($request) {
                    $d->where('name', 'like', '%' . $request->q . '%')
                        ->orWhere('serial_number', 'like', '%' . $request->q . '%');
                })
                    ->orWhere('description', 'like', '%' . $request->q . '%');
            });
        }

        /* ================= STATUS ================= */
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        /* ================= PROJECT ================= */
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        /* ================= TECHNICIAN ================= */
        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        /* ================= FROM PM ================= */
        if ($request->boolean('from_pm')) {
            $query->whereNotNull('pm_record_id');
        }

        $breakdowns = $query
            ->paginate(10)
            ->withQueryString();

        // For filters dropdowns
        $projects = Project::select('id', 'name')->get();
        $technicians = User::role('technician')->select('id', 'name')->get();

        return view('breakdowns.index', compact(
            'breakdowns',
            'projects',
            'technicians'
        ));
    }




    /**
     * صفحة إنشاء بلاغ جديد
     */
    public function create()
    {
        $devices = Device::with('project')->get();

        return view('breakdowns.create', compact('devices'));
    }

    /**
     * حفظ البلاغ في قاعدة البيانات
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_id' => 'required|exists:devices,id',
            'issue_description' => 'required|string',
        ]);

        $device = Device::findOrFail($validated['device_id']);

        $breakdown = Breakdown::create([
            'device_id' => $device->id,
            'project_id' => $device->project_id,
            'issue_description' => $validated['issue_description'],
            'status' => 'open',
            'reported_by' => auth()->id(),
        ]);

        $breakdown->logs()->create([
            'user_id' => auth()->id(),
            'action' => 'created',
            'notes' => 'Breakdown reported',
        ]);



        return redirect()->route('breakdowns.index')
            ->with('success', 'Breakdown created successfully.');
    }

    /**
     * عرض تفاصيل البلاغ
     */


    public function show($id)
    {
        $breakdown = Breakdown::with([
            'device',
            'project',
            'assignedUser'
        ])->findOrFail($id);

        $technicians = User::role('technician')->select('id', 'name')->get();

        // جميع قطع الغيار (لـ Issue)
        $spareParts = SparePart::orderBy('name')->get();

        /**
         * =========================
         * Returnable Spare Parts
         * issued - returned > 0
         * =========================
         */
        $returnableParts = SparePart::select(
            'spare_parts.id',
            'spare_parts.name',
            DB::raw('
                SUM(
                    CASE 
                        WHEN spare_part_usages.type = "issue" THEN spare_part_usages.quantity
                        WHEN spare_part_usages.type = "return" THEN -spare_part_usages.quantity
                        ELSE 0
                    END
                ) AS remaining_qty
            ')
        )
            ->join('spare_part_usages', 'spare_parts.id', '=', 'spare_part_usages.spare_part_id')
            ->where('spare_part_usages.breakdown_id', $breakdown->id)
            ->groupBy('spare_parts.id', 'spare_parts.name')
            ->having('remaining_qty', '>', 0)
            ->orderBy('spare_parts.name')
            ->get();

        // سجل الحركات
        $spareUsages = SparePartUsage::with(['sparePart', 'performer'])
            ->where('breakdown_id', $breakdown->id)
            ->latest()
            ->get();

        return view('breakdowns.show', compact(
            'breakdown',
            'technicians',
            'spareParts',
            'returnableParts',
            'spareUsages'
        ));
    }


    public function assign(Request $request, Breakdown $breakdown)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id'
        ]);

        $breakdown->update([
            'assigned_to' => $request->assigned_to,
            'status' => 'assigned',
            'assigned_at' => now(),
        ]);

        $breakdown->logs()->create([
            'user_id' => auth()->id(),
            'action' => 'assigned',
            'notes' => 'Assigned to technician ID: ' . $request->assigned_to,
        ]);




        return back()->with('success', 'Breakdown assigned successfully.');
    }


    public function start(Breakdown $breakdown)
    {
        abort_unless(auth()->user()->can('work breakdowns'), 403);

        abort_unless(auth()->id() === $breakdown->assigned_to, 403);

        $breakdown->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);

        return back()->with('success', 'Work started.');
    }

    public function resolve(Request $request, Breakdown $breakdown)
    {
        abort_unless(auth()->user()->can('work breakdowns'), 403);

        abort_unless(auth()->id() === $breakdown->assigned_to, 403);

        // فقط المكلّف أو الأدمن
        abort_if(
            auth()->id() !== $breakdown->assigned_to &&
            !auth()->user()->hasRole('admin'),
            403
        );

        $request->validate([
            'report_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'scan_image' => 'nullable|string',
        ]);

        // ❌ لا Resolve بدون تقرير
        if (!$request->hasFile('report_file') && !$request->scan_image) {
            return back()->withErrors([
                'report' => 'Service report (upload or scan) is required to resolve breakdown.'
            ]);
        }

        /* =============================
            📎 FILE UPLOAD
        ============================== */
        $storedPath = null;

        if ($request->hasFile('report_file')) {
            $storedPath = $request->file('report_file')
                ->store('breakdown_reports', 'public');
        }

        /* =============================
            📷 SCAN IMAGE
        ============================== */
        if ($request->scan_image) {
            $imageData = preg_replace(
                '/^data:image\/\w+;base64,/',
                '',
                $request->scan_image
            );

            $imageData = base64_decode($imageData);

            $fileName = 'breakdown_reports/scan_' . now()->timestamp . '.png';
            \Storage::disk('public')->put($fileName, $imageData);

            $storedPath = $fileName;
        }

        /* =============================
            🔄 UPDATE BREAKDOWN
        ============================== */
        $breakdown->update([
            'status' => 'resolved',
            'engineer_report' => $storedPath,
            'completed_at' => now(),
        ]);


        return redirect()
            ->route('breakdowns.show', $breakdown)
            ->with('success', 'Breakdown resolved successfully');
    }



    public function close(Breakdown $breakdown)
    {
        $breakdown->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);

        return back()->with('success', 'Breakdown closed.');
    }


    public function issueSparePart(Request $request, Breakdown $breakdown)
    {
        abort_unless(
            auth()->user()->can('manage spare parts') ||
            auth()->user()->can('work breakdowns'),
            403
        );

        $data = $request->validate([
            'issue_spare_part_id' => 'required|exists:spare_parts,id',
            'issue_quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($data, $breakdown) {

            $part = SparePart::lockForUpdate()
                ->findOrFail($data['issue_spare_part_id']);

            if ($part->quantity < $data['issue_quantity']) {
                abort(422, 'Not enough stock');
            }

            // خصم من المخزون
            $part->decrement('quantity', $data['issue_quantity']);

            // تسجيل حركة صرف
            SparePartUsage::create([
                'spare_part_id' => $part->id,
                'breakdown_id' => $breakdown->id,
                'quantity' => $data['issue_quantity'], // موجبة
                'type' => 'issue',
                'performed_by' => auth()->id(),
            ]);
        });

        return back()->with('success', 'Spare part issued successfully.');
    }


    public function returnSparePart(Request $request, Breakdown $breakdown)
    {
        abort_unless(
            auth()->user()->can('manage spare parts') ||
            auth()->user()->can('work breakdowns'),
            403
        );

        $data = $request->validate([
            'return_spare_part_id' => 'required|exists:spare_parts,id',
            'return_quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($data, $breakdown) {

            $part = SparePart::lockForUpdate()
                ->findOrFail($data['return_spare_part_id']);

            // إجمالي ما صُرف
            $issued = SparePartUsage::where('breakdown_id', $breakdown->id)
                ->where('spare_part_id', $part->id)
                ->where('type', 'issue')
                ->sum('quantity');

            // إجمالي ما أُعيد
            $returned = SparePartUsage::where('breakdown_id', $breakdown->id)
                ->where('spare_part_id', $part->id)
                ->where('type', 'return')
                ->sum('quantity');

            $remaining = $issued - $returned;

            if ($data['return_quantity'] > $remaining) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'return_quantity' => "You can return maximum {$remaining} item(s) for this part."
                ]);
            }


            // ✅ زيادة المخزون
            $part->increment('quantity', $data['return_quantity']);

            // تسجيل حركة إرجاع
            SparePartUsage::create([
                'spare_part_id' => $part->id,
                'breakdown_id' => $breakdown->id,
                'quantity' => $data['return_quantity'], // موجبة
                'type' => 'return',
                'performed_by' => auth()->id(),
            ]);
        });

        return back()->with('success', 'Spare part returned to stock.');
    }




}
