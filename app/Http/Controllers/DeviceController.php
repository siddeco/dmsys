<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index()
    {
        // Fetch list of devices
        $devices = Device::paginate(10);

        return view('devices.index', compact('devices'));
    }

    public function create()
    {
        return view('devices.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'serial_number' => 'required|unique:devices,serial_number',
            'model' => 'nullable|string',
            'manufacturer' => 'nullable|string',
            'location' => 'nullable|string',
            'installation_date' => 'nullable|date',
            'status' => 'required',

            // translated fields
            'name_en' => 'required|string',
            'name_ar' => 'required|string',
        ]);

        // Create new device
        $device = new Device();

        // Save translated name as JSON (Spatie)
        $device->setTranslations('name', [
            'en' => $request->name_en,
            'ar' => $request->name_ar,
        ]);

        // Save normal fields
        $device->serial_number = $request->serial_number;
        $device->model = $request->model;
        $device->manufacturer = $request->manufacturer;
        $device->location = $request->location;
        $device->installation_date = $request->installation_date;
        $device->status = $request->status;

        $device->save();

        return redirect()->route('devices.index')
                         ->with('success', __('Device created successfully'));
    }
}
