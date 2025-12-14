<?php

namespace App\Http\Controllers;

use App\Models\User; 
use App\Models\Breakdown;
use App\Models\Device;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BreakdownController extends Controller
{

     use AuthorizesRequests;
    /**
     * عرض كل بلاغات الأعطال
     */
 public function index(Request $request)
{
    $query = Breakdown::with(['device', 'project', 'assignedUser']);

    // ✅ فلترة الحالة
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // ✅ فلترة الفني (اختياري لاحقًا)
    if ($request->filled('assigned_to')) {
        $query->where('assigned_to', $request->assigned_to);
    }

    $breakdowns = $query
        ->orderByDesc('created_at')
        ->paginate(10)
        ->withQueryString(); // ⭐⭐⭐ الحل السحري

    return view('breakdowns.index', compact('breakdowns'));
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

        Breakdown::create([
            'device_id' => $device->id,
            'project_id' => $device->project_id, // جلب المشروع تلقائيًا
            'issue_description' => $validated['issue_description'],
            'status' => 'new',
        ]);

        return redirect()->route('breakdowns.index')
                         ->with('success', 'Breakdown created successfully.');
    }

    /**
     * عرض تفاصيل البلاغ
     */


public function show($id)
{
    $breakdown = Breakdown::with(['device', 'project', 'assignedUser'])
        ->findOrFail($id);

    // 👇 جلب الفنيين فقط
    $technicians = User::role('technician')->get();

    return view('breakdowns.show', compact(
        'breakdown',
        'technicians'
    ));
}


    public function assign(Request $request, Breakdown $breakdown)
{
    $request->validate([
        'assigned_to' => 'required|exists:users,id'
    ]);

    $breakdown->update([
        'assigned_to' => $request->assigned_to,
        'status'      => 'assigned',
        'assigned_at' => now(),
    ]);

    return back()->with('success', 'Breakdown assigned successfully.');
}


public function start(Breakdown $breakdown)
{
    abort_unless(auth()->user()->can('work breakdowns'), 403);

    abort_unless(auth()->id() === $breakdown->assigned_to, 403);

    $breakdown->update([
        'status'     => 'in_progress',
        'started_at'=> now(),
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
        'scan_image'  => 'nullable|string',
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
        'status'          => 'resolved',
        'engineer_report' => $storedPath,
        'completed_at'    => now(),
    ]);

    return redirect()
        ->route('breakdowns.show', $breakdown)
        ->with('success', 'Breakdown resolved successfully');
}



public function close(Breakdown $breakdown)
{
    $breakdown->update([
        'status'    => 'closed',
        'closed_at'=> now(),
    ]);

    return back()->with('success', 'Breakdown closed.');
}




}
