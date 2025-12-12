<?php

namespace App\Http\Controllers;

use App\Models\User; 
use App\Models\Breakdown;
use App\Models\Device;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BreakdownController extends Controller
{
    /**
     * عرض كل بلاغات الأعطال
     */
   public function index(Request $request)
{
    $query = Breakdown::with(['device', 'project']);

    // 🔴 Open only
    if ($request->get('status')) {
        $query->where('status', $request->status);
    }

    // 🔴 Critical (open > 7 days)
    if ($request->get('critical')) {
        $query->where('status', 'open')
              ->whereDate('created_at', '<=', now()->subDays(7));
    }

    $breakdowns = $query->latest()->paginate(10);

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
    abort_if(auth()->id() !== $breakdown->assigned_to, 403);

    $breakdown->update([
        'status'     => 'in_progress',
        'started_at'=> now(),
    ]);

    return back()->with('success', 'Work started.');
}

public function resolve(Request $request, Breakdown $breakdown)
{
    abort_if(auth()->id() !== $breakdown->assigned_to, 403);

    $request->validate([
        'resolution_notes' => 'required|string'
    ]);

    $breakdown->update([
        'status'           => 'resolved',
        'resolved_at'      => now(),
        'resolution_notes'=> $request->resolution_notes,
    ]);

    return back()->with('success', 'Breakdown resolved.');
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
