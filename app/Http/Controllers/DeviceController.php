<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class DeviceController extends Controller
{
    /**
     * قائمة الأجهزة
     */
    public function index(Request $request)
    {
        $query = Device::with('project', 'assignedTechnician')
            ->where('is_archived', false);

        // 🔍 Search
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('serial_number', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('manufacturer', 'like', "%{$search}%")
                    ->orWhere('name->en', 'like', "%{$search}%");
            });
        }

        // 📊 Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 🏢 Project filter
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // 🔧 Device Type filter
        if ($request->filled('device_type')) {
            $query->where('device_type', $request->device_type);
        }

        // 🏷️ Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // 📅 Sort
        $sort = $request->get('sort', 'id');
        $order = $request->get('order', 'desc');
        $query->orderBy($sort, $order);

        $devices = $query->paginate(15)->withQueryString();
        $projects = Project::all();

        // إحصائيات
        $stats = [
            'total' => Device::where('is_archived', false)->count(),
            'active' => Device::where('is_archived', false)->where('status', 'active')->count(),
            'maintenance' => Device::where('is_archived', false)->where('status', 'under_maintenance')->count(),
            'projects' => Project::count(),
        ];

        return view('devices.index', compact('devices', 'projects', 'stats'));
    }

    /**
     * صفحة إضافة جهاز جديد
     */
    public function create()
    {
        abort_unless(auth()->user()->can('manage devices'), 403);

        $projects = Project::all();
        $technicians = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['technician', 'maintenance_engineer', 'supervisor']);
        })->get();

        return view('devices.create', compact('projects', 'technicians'));
    }

    /**
     * صفحة تعديل جهاز
     */
    public function edit($id)
    {
        abort_unless(auth()->user()->can('manage devices'), 403);

        $device = Device::findOrFail($id);
        $projects = Project::all();
        $technicians = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['technician', 'maintenance_engineer', 'supervisor']);
        })->get();

        return view('devices.edit', compact('device', 'projects', 'technicians'));
    }

    /**
     * تحديث بيانات الجهاز
     */
    public function update(Request $request, $id)
    {
        abort_unless(auth()->user()->can('manage devices'), 403);

        $device = Device::findOrFail($id);

        // قيم افتراضية للتحقق
        $deviceTypes = [
            'xray',
            'ultrasound',
            'mri',
            'ct_scanner',
            'ventilator',
            'monitor',
            'defibrillator',
            'analyzer',
            'centrifuge',
            'microscope',
            'autoclave',
            'incubator',
            'other'
        ];

        $categories = [
            'imaging',
            'monitoring',
            'laboratory',
            'therapeutic',
            'surgical',
            'diagnostic',
            'dental',
            'ophthalmic',
            'other'
        ];

        $regions = [
            'الرياض',
            'مكة المكرمة',
            'المدينة المنورة',
            'القصيم',
            'الشرقية',
            'عسير',
            'تبوك',
            'حائل',
            'الحدود الشمالية',
            'جازان',
            'نجران',
            'الباحة',
            'الجوف'
        ];

        $validated = $request->validate([
            // المعلومات الأساسية
            'name' => 'required|string|max:200',
            'serial_number' => [
                'required',
                'string',
                'max:100',
                Rule::unique('devices', 'serial_number')->ignore($device->id)
            ],
            'model' => 'nullable|string|max:100',
            'manufacturer' => 'nullable|string|max:100',

            // التصنيف
            'device_type' => ['nullable', Rule::in($deviceTypes)],
            'category' => ['nullable', Rule::in($categories)],

            // الموقع
            'project_id' => 'nullable|exists:projects,id',
            'location' => 'nullable|string|max:200',
            'room_number' => 'nullable|string|max:50',
            'floor' => 'nullable|string|max:50',
            'building' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'region' => ['nullable', Rule::in($regions)],

            // التواريخ
            'purchase_date' => 'nullable|date',
            'installation_date' => 'nullable|date',
            'warranty_expiry' => 'nullable|date|after_or_equal:purchase_date',
            'last_calibration_date' => 'nullable|date',
            'next_calibration_date' => 'nullable|date|after_or_equal:last_calibration_date',

            // الحالة
            'status' => ['required', Rule::in(['active', 'inactive', 'under_maintenance', 'out_of_service'])],
            'condition' => ['required', Rule::in(['excellent', 'good', 'fair', 'poor'])],

            // الإدارة
            'assigned_to' => 'nullable|exists:users,id',
            'purchase_price' => 'nullable|numeric|min:0',
            'current_value' => 'nullable|numeric|min:0',
            'depreciation_rate' => 'nullable|numeric|between:0,100',

            // الضمان والصيانة
            'service_provider' => 'nullable|string|max:200',
            'service_contract_number' => 'nullable|string|max:100',
            'preventive_maintenance_frequency' => 'nullable|integer|min:1',

            // المواصفات الفنية
            'power_requirements' => 'nullable|string|max:200',
            'dimensions' => 'nullable|string|max:100',
            'weight' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // تحديث البيانات (الاسم فقط كـ JSON)
        $device->update([
            'serial_number' => $validated['serial_number'],
            'model' => $validated['model'],
            'manufacturer' => $validated['manufacturer'],
            'device_type' => $validated['device_type'],
            'category' => $validated['category'],
            'project_id' => $validated['project_id'],
            'location' => $validated['location'],
            'room_number' => $validated['room_number'],
            'floor' => $validated['floor'],
            'building' => $validated['building'],
            'city' => $validated['city'],
            'region' => $validated['region'],
            'purchase_date' => $validated['purchase_date'],
            'installation_date' => $validated['installation_date'],
            'warranty_expiry' => $validated['warranty_expiry'],
            'last_calibration_date' => $validated['last_calibration_date'],
            'next_calibration_date' => $validated['next_calibration_date'],
            'status' => $validated['status'],
            'condition' => $validated['condition'],
            'assigned_to' => $validated['assigned_to'],
            'purchase_price' => $validated['purchase_price'],
            'current_value' => $validated['current_value'],
            'depreciation_rate' => $validated['depreciation_rate'],
            'service_provider' => $validated['service_provider'],
            'service_contract_number' => $validated['service_contract_number'],
            'preventive_maintenance_frequency' => $validated['preventive_maintenance_frequency'],
            'power_requirements' => $validated['power_requirements'],
            'dimensions' => $validated['dimensions'],
            'weight' => $validated['weight'],
            'notes' => $validated['notes'],
        ]);

        // تحديث الاسم كـ JSON (بالإنجليزية فقط)
        $device->setTranslations('name', [
            'en' => $validated['name'],
            'ar' => $validated['name'] // نفس الاسم بالإنجليزية
        ]);

        $device->save();

        return redirect()
            ->route('devices.show', $device->id)
            ->with('success', 'تم تحديث بيانات الجهاز بنجاح.');
    }

    /**
     * تخزين الجهاز في قاعدة البيانات
     */
    public function store(Request $request)
    {
        abort_unless(auth()->user()->can('manage devices'), 403);

        // قيم افتراضية للتحقق
        $deviceTypes = [
            'xray',
            'ultrasound',
            'mri',
            'ct_scanner',
            'ventilator',
            'monitor',
            'defibrillator',
            'analyzer',
            'centrifuge',
            'microscope',
            'autoclave',
            'incubator',
            'other'
        ];

        $categories = [
            'imaging',
            'monitoring',
            'laboratory',
            'therapeutic',
            'surgical',
            'diagnostic',
            'dental',
            'ophthalmic',
            'other'
        ];

        $regions = [
            'الرياض',
            'مكة المكرمة',
            'المدينة المنورة',
            'القصيم',
            'الشرقية',
            'عسير',
            'تبوك',
            'حائل',
            'الحدود الشمالية',
            'جازان',
            'نجران',
            'الباحة',
            'الجوف'
        ];

        $validated = $request->validate([
            // المعلومات الأساسية
            'name' => 'required|string|max:200',
            'serial_number' => 'required|string|max:100|unique:devices,serial_number',
            'model' => 'nullable|string|max:100',
            'manufacturer' => 'nullable|string|max:100',

            // التصنيف
            'device_type' => ['nullable', Rule::in($deviceTypes)],
            'category' => ['nullable', Rule::in($categories)],

            // الموقع
            'project_id' => 'nullable|exists:projects,id',
            'location' => 'nullable|string|max:200',
            'room_number' => 'nullable|string|max:50',
            'floor' => 'nullable|string|max:50',
            'building' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'region' => ['nullable', Rule::in($regions)],

            // التواريخ
            'purchase_date' => 'nullable|date',
            'installation_date' => 'nullable|date',
            'warranty_expiry' => 'nullable|date|after_or_equal:purchase_date',
            'last_calibration_date' => 'nullable|date',
            'next_calibration_date' => 'nullable|date|after_or_equal:last_calibration_date',

            // الحالة
            'status' => ['required', Rule::in(['active', 'inactive', 'under_maintenance', 'out_of_service'])],
            'condition' => ['required', Rule::in(['excellent', 'good', 'fair', 'poor'])],

            // الإدارة
            'assigned_to' => 'nullable|exists:users,id',
            'purchase_price' => 'nullable|numeric|min:0',
            'current_value' => 'nullable|numeric|min:0',
            'depreciation_rate' => 'nullable|numeric|between:0,100',

            // الضمان والصيانة
            'service_provider' => 'nullable|string|max:200',
            'service_contract_number' => 'nullable|string|max:100',
            'preventive_maintenance_frequency' => 'nullable|integer|min:1',

            // المواصفات الفنية
            'power_requirements' => 'nullable|string|max:200',
            'dimensions' => 'nullable|string|max:100',
            'weight' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // إنشاء الجهاز
        $device = Device::create([
            'serial_number' => $validated['serial_number'],
            'model' => $validated['model'],
            'manufacturer' => $validated['manufacturer'],
            'device_type' => $validated['device_type'],
            'category' => $validated['category'],
            'project_id' => $validated['project_id'],
            'location' => $validated['location'],
            'room_number' => $validated['room_number'],
            'floor' => $validated['floor'],
            'building' => $validated['building'],
            'city' => $validated['city'],
            'region' => $validated['region'],
            'purchase_date' => $validated['purchase_date'],
            'installation_date' => $validated['installation_date'],
            'warranty_expiry' => $validated['warranty_expiry'],
            'last_calibration_date' => $validated['last_calibration_date'],
            'next_calibration_date' => $validated['next_calibration_date'],
            'status' => $validated['status'],
            'condition' => $validated['condition'],
            'assigned_to' => $validated['assigned_to'],
            'purchase_price' => $validated['purchase_price'],
            'current_value' => $validated['current_value'],
            'depreciation_rate' => $validated['depreciation_rate'],
            'service_provider' => $validated['service_provider'],
            'service_contract_number' => $validated['service_contract_number'],
            'preventive_maintenance_frequency' => $validated['preventive_maintenance_frequency'],
            'power_requirements' => $validated['power_requirements'],
            'dimensions' => $validated['dimensions'],
            'weight' => $validated['weight'],
            'notes' => $validated['notes'],
            'is_archived' => false,
        ]);

        // إضافة الاسم كـ JSON (بالإنجليزية فقط)
        $device->setTranslations('name', [
            'en' => $validated['name'],
            'ar' => $validated['name'] // نفس الاسم بالإنجليزية
        ]);

        $device->save();

        return redirect()
            ->route('devices.show', $device->id)
            ->with('success', 'تم إضافة الجهاز بنجاح.');
    }

    /**
     * أرشفة جهاز
     */
    public function archive(Device $device)
    {
        abort_unless(auth()->user()->can('manage devices'), 403);

        $device->update([
            'is_archived' => true,
            'status' => 'inactive',
        ]);

        return redirect()
            ->route('devices.index')
            ->with('success', 'تم أرشفة الجهاز بنجاح.');
    }

    /**
     * استعادة جهاز مؤرشف
     */
    public function restore(Device $device)
    {
        abort_unless(auth()->user()->can('manage devices'), 403);

        $device->update([
            'is_archived' => false,
            'status' => 'active',
        ]);

        return redirect()
            ->back()
            ->with('success', 'تم استعادة الجهاز بنجاح.');
    }

    /**
     * قائمة الأجهزة المؤرشفة
     */
    /**
     * قائمة الأجهزة المؤرشفة
     */
    public function archived(Request $request)
    {
        abort_unless(auth()->user()->can('manage devices'), 403);

        $query = Device::with('project')
            ->where('is_archived', true);

        // 🔍 Search
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('serial_number', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('manufacturer', 'like', "%{$search}%")
                    ->orWhere('name->en', 'like', "%{$search}%");
            });
        }

        // 🏢 Project filter
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // 🔧 Device Type filter
        if ($request->filled('device_type')) {
            $query->where('device_type', $request->device_type);
        }

        $devices = $query->latest()->paginate(15)->withQueryString();
        $projects = Project::all();

        return view('devices.archived', compact('devices', 'projects'));
    }

    /**
     * عرض تفاصيل الجهاز
     */
    public function show($id)
    {
        $device = Device::with([
            'project',
            'assignedTechnician',
            'pmPlans' => function ($query) {
                $query->where('status', '!=', 'completed')->latest()->limit(5);
            },
            'pmRecords' => function ($query) {
                $query->latest()->limit(5);
            },
            'breakdowns' => function ($query) {
                $query->latest()->limit(5);
            },
            'calibrations' => function ($query) {
                $query->latest()->limit(5);
            },
            'sparePartUsages' => function ($query) {
                $query->with('sparePart')->latest()->limit(10);
            }
        ])->findOrFail($id);

        // إحصائيات الجهاز
        $deviceStats = $device->stats;

        return view('devices.show', compact('device', 'deviceStats'));
    }

    /**
     * حذف جهاز (Soft Delete)
     */
    public function destroy(Device $device)
    {
        abort_unless(auth()->user()->can('manage devices'), 403);

        $device->delete();

        return redirect()
            ->route('devices.index')
            ->with('success', 'تم حذف الجهاز بنجاح.');
    }

    /**
     * تحديث حالة الجهاز بسرعة
     */
    public function updateStatus(Request $request, Device $device)
    {
        abort_unless(auth()->user()->can('manage devices'), 403);

        $request->validate([
            'status' => ['required', Rule::in(['active', 'inactive', 'under_maintenance', 'out_of_service'])]
        ]);

        $device->update([
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث حالة الجهاز بنجاح.',
            'status' => $device->status
        ]);
    }

    /**
     * تحديث الفني المسؤول
     */
    public function updateAssignedTechnician(Request $request, Device $device)
    {
        abort_unless(auth()->user()->can('manage devices'), 403);

        $request->validate([
            'assigned_to' => 'nullable|exists:users,id'
        ]);

        $device->update([
            'assigned_to' => $request->assigned_to
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الفني المسؤول بنجاح.',
            'technician' => $device->assignedTechnician ? $device->assignedTechnician->name : null
        ]);
    }

    
}