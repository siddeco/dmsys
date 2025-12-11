<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Project; // استيراد الموديل الصحيح
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    /**
     * قائمة الأجهزة
     */
   public function index()
{
    $devices = Device::with('project')->paginate(10);
    $projects = \App\Models\Project::all(); // <-- أضف هذا السطر

    return view('devices.index', compact('devices', 'projects'));
}

    /**
     * صفحة إضافة جهاز جديد
     */
    public function create()
    {
        $projects = Project::all(); // جلب كل المشاريع
        return view('devices.create', compact('projects'));
    }

    public function edit($id)
{
    $device = Device::findOrFail($id);
    $projects = Project::all();

    return view('devices.edit', compact('device', 'projects'));
}

public function update(Request $request, $id)
{
    $device = Device::findOrFail($id);

    $validated = $request->validate([
        'serial_number' => 'required|unique:devices,serial_number,' . $device->id,
        'model' => 'nullable|string',
        'manufacturer' => 'nullable|string',
        'location' => 'nullable|string',
        'installation_date' => 'nullable|date',
        'status' => 'required',
        'project_id' => 'required|exists:projects,id',

        // Translations
        'name_en' => 'required|string',
        'name_ar' => 'required|string',
    ]);

    // تحديث البيانات الأساسية
    $device->update([
        'serial_number' => $validated['serial_number'],
        'model' => $validated['model'],
        'manufacturer' => $validated['manufacturer'],
        'location' => $validated['location'],
        'installation_date' => $validated['installation_date'],
        'status' => $validated['status'],
        'project_id' => $validated['project_id'],
    ]);

    // تحديث الترجمة
    $device->translateOrNew('en')->name = $validated['name_en'];
    $device->translateOrNew('ar')->name = $validated['name_ar'];
    $device->save();

    return redirect()
        ->route('devices.index')
        ->with('success', 'Device updated successfully.');
}


    /**
     * تخزين الجهاز في قاعدة البيانات
     */
   public function store(Request $request)
{
    $validated = $request->validate([
        'serial_number' => 'required|unique:devices,serial_number',
        'model' => 'nullable|string',
        'manufacturer' => 'nullable|string',
        'location' => 'nullable|string',
        'installation_date' => 'nullable|date',
        'status' => 'required',

        'project_id' => 'required|exists:projects,id',

        'name_en' => 'required|string',
        'name_ar' => 'required|string',
        'description_en' => 'nullable|string',
        'description_ar' => 'nullable|string',
    ]);

    $device = Device::create([
        'serial_number' => $validated['serial_number'],
        'model'         => $validated['model'] ?? null,
        'manufacturer'  => $validated['manufacturer'] ?? null,
        'location'      => $validated['location'] ?? null,
        'installation_date' => $validated['installation_date'] ?? null,
        'status'        => $validated['status'],
        'project_id'    => $validated['project_id'],

        'name' => [
            'en' => $validated['name_en'],
            'ar' => $validated['name_ar'],
        ],
        'description' => [
            'en' => $validated['description_en'] ?? '',
            'ar' => $validated['description_ar'] ?? '',
        ],
    ]);

    return redirect()->route('devices.index')
                     ->with('success', 'Device created successfully.');
}

}