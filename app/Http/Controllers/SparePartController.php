<?php

namespace App\Http\Controllers;

use App\Models\SparePart;
use App\Models\Device;
use Illuminate\Http\Request;

class SparePartController extends Controller
{
    /**
     * عرض قائمة القطع
     */
    public function index(Request $request)
{
    $query = SparePart::with('device');

    // 🔴 Low Stock
    if ($request->get('low_stock')) {
        $query->whereColumn('quantity', '<=', 'alert_threshold');
    }

    // 🔴 Out of Stock
    if ($request->get('out_of_stock')) {
        $query->where('quantity', 0);
    }

    $parts = $query->latest()->paginate(10);

    return view('spare_parts.index', compact('parts'));
}


    /**
     * صفحة إنشاء قطعة جديدة
     */
    public function create()
    {
        $devices = Device::all();
        return view('spare_parts.create', compact('devices'));
    }

    /**
     * تخزين القطعة الجديدة
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string',
            'part_number'     => 'nullable|string',
            'manufacturer'    => 'nullable|string',
            'device_id'       => 'nullable|exists:devices,id',
            'quantity'        => 'required|integer|min:0',
            'alert_threshold' => 'required|integer|min:0',
            'description'     => 'nullable|string',
        ]);

        SparePart::create($validated);

        return redirect()->route('spare_parts.index')
            ->with('success', 'Spare part added successfully.');
    }

    /**
     * صفحة تعديل القطعة
     */
    public function edit($id)
    {
        $part = SparePart::findOrFail($id);
        $devices = Device::all();

        return view('spare_parts.edit', compact('part', 'devices'));
    }

    /**
     * تحديث بيانات القطعة
     */
    public function update(Request $request, $id)
    {
        $part = SparePart::findOrFail($id);

        $validated = $request->validate([
            'name'            => 'required|string',
            'part_number'     => 'nullable|string',
            'manufacturer'    => 'nullable|string',
            'device_id'       => 'nullable|exists:devices,id',
            'quantity'        => 'required|integer|min:0',
            'alert_threshold' => 'required|integer|min:0',
            'description'     => 'nullable|string',
        ]);

        $part->update($validated);

        return redirect()->route('spare_parts.index')
            ->with('success', 'Spare part updated successfully.');
    }

    /**
     * حذف القطعة
     */
    public function destroy($id)
    {
        SparePart::findOrFail($id)->delete();

        return redirect()->route('spare_parts.index')
            ->with('success', 'Spare part deleted.');
    }
}
