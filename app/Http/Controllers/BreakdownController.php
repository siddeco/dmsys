<?php

namespace App\Http\Controllers;

use App\Models\Breakdown;
use App\Models\Device;
use Illuminate\Http\Request;

class BreakdownController extends Controller
{
    /**
     * عرض كل بلاغات الأعطال
     */
    public function index()
    {
        $breakdowns = Breakdown::with(['device', 'project', 'assignedEngineer'])
                               ->orderBy('id', 'desc')
                               ->paginate(10);

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
        $breakdown = Breakdown::with(['device', 'project', 'assignedEngineer'])
                              ->findOrFail($id);

        return view('breakdowns.show', compact('breakdown'));
    }
}
