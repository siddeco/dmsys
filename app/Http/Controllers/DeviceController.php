<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Project; // استيراد الموديل الصحيح
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

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
       
        abort_unless(auth()->user()->can('manage devices'), 403);
        $projects = Project::all(); // جلب كل المشاريع
        return view('devices.create', compact('projects'));
    }

    public function edit($id)
{
    abort_unless(auth()->user()->can('manage devices'), 403);
    $device = Device::findOrFail($id);
    

    $projects = Project::all();

    return view('devices.edit', compact('device', 'projects'));
}

public function update(Request $request, $id)
{
    abort_unless(auth()->user()->can('manage devices'), 403);

    $device = Device::findOrFail($id);

    $validated = $request->validate([
        'serial_number' => 'required|unique:devices,serial_number,' . $device->id,
        'model' => 'nullable|string',
        'manufacturer' => 'nullable|string',
        'location' => 'nullable|string',
        'city' => 'required|string',
        'installation_date' => 'nullable|date',
        'status' => 'required',
        'project_id' => 'required|exists:projects,id',
        'name_en' => 'required|string',
        'name_ar' => 'required|string',
    ]);

    $device->update([
        'serial_number' => $validated['serial_number'],
        'model' => $validated['model'] ?? null,
        'manufacturer' => $validated['manufacturer'] ?? null,
        'location' => $validated['location'] ?? null,
        'city' => $validated['city'],
        'installation_date' => $validated['installation_date'] ?? null,
        'status' => $validated['status'],
        'project_id' => $validated['project_id'],
    ]);

    $device->setTranslations('name', [
        'en' => $validated['name_en'],
        'ar' => $validated['name_ar'],
    ]);

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
    abort_unless(auth()->user()->can('manage devices'), 403);

    $validated = $request->validate([
        'serial_number' => 'required|unique:devices,serial_number',
        'model' => 'nullable|string',
        'manufacturer' => 'nullable|string',
        'location' => 'nullable|string',
        'city' => 'required|string',
        'installation_date' => 'nullable|date',
        'status' => 'required',
        'project_id' => 'required|exists:projects,id',
        'name_en' => 'required|string',
        'name_ar' => 'required|string',
    ]);

    $device = Device::create([
        'serial_number' => $validated['serial_number'],
        'model' => $validated['model'] ?? null,
        'manufacturer' => $validated['manufacturer'] ?? null,
        'location' => $validated['location'] ?? null,
        'city' => $validated['city'],
        'installation_date' => $validated['installation_date'] ?? null,
        'status' => $validated['status'],
        'project_id' => $validated['project_id'],
    ]);

    // ✅ الطريقة الصحيحة مع Spatie
    $device->setTranslations('name', [
        'en' => $validated['name_en'],
        'ar' => $validated['name_ar'],
    ]);

    $device->save();

    return redirect()
        ->route('devices.index')
        ->with('success', 'Device created successfully.');
}


}