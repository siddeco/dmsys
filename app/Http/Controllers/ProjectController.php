<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    /**
     * قائمة المشاريع
     */
    public function index(Request $request)
    {
        $query = Project::with(['client', 'manager']);

        // 🔍 Search
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('client_name', 'like', "%{$search}%")
                    ->orWhere('contract_number', 'like', "%{$search}%");
            });
        }

        // 📊 Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 🏷️ Priority filter
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // 🏢 Region filter
        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }

        // 👥 Client type filter
        if ($request->filled('client_type')) {
            $query->where('client_type', $request->client_type);
        }

        // 📅 Sort
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');
        $query->orderBy($sort, $order);

        $projects = $query->paginate(15)->withQueryString();

        // إحصائيات
        $stats = [
            'total' => Project::count(),
            'active' => Project::where('status', 'active')->count(),
            'completed' => Project::where('status', 'completed')->count(),
            'overdue' => Project::overdue()->count(),
            'ending_soon' => Project::endingSoon()->count(),
        ];

        return view('projects.index', compact('projects', 'stats'));
    }

    /**
     * صفحة إضافة مشروع جديد
     */
    public function create()
    {
        abort_unless(auth()->user()->can('manage projects'), 403);

        $clients = User::whereHas('roles', function ($query) {
            $query->where('name', 'client');
        })->get();

        $managers = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['manager', 'admin', 'supervisor']);
        })->get();

        return view('projects.create', compact('clients', 'managers'));
    }

    /**
     * تخزين المشروع في قاعدة البيانات
     */
    public function store(Request $request)
    {
        abort_unless(auth()->user()->can('manage projects'), 403);

        // قيم افتراضية للتحقق
        $clientTypes = ['hospital', 'clinic', 'laboratory', 'pharmacy', 'government', 'company', 'other'];
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
            'code' => 'nullable|string|max:50|unique:projects,code',

            // العميل
            'client_id' => 'nullable|exists:users,id',
            'client_name' => 'nullable|string|max:200',
            'client_type' => ['nullable', Rule::in($clientTypes)],

            // الوصف
            'description' => 'nullable|string',

            // الموقع
            'city' => 'nullable|string|max:100',
            'region' => ['nullable', Rule::in($regions)],
            'address' => 'nullable|string',

            // التواريخ
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'actual_end_date' => 'nullable|date|after_or_equal:start_date',

            // الإدارة
            'project_manager_id' => 'nullable|exists:users,id',
            'status' => ['required', Rule::in(['active', 'completed', 'on_hold', 'cancelled'])],
            'priority' => ['required', Rule::in(['low', 'medium', 'high'])],

            // المالية
            'budget' => 'nullable|numeric|min:0',
            'actual_cost' => 'nullable|numeric|min:0',

            // العقد
            'contract_number' => 'nullable|string|max:100',
            'contract_value' => 'nullable|numeric|min:0',
            'warranty_period' => 'nullable|integer|min:0',

            // ملاحظات
            'notes' => 'nullable|string',
        ]);

        // إنشاء المشروع
        $project = Project::create(array_merge($validated, [
            'is_active' => $validated['status'] === 'active',
        ]));

        return redirect()
            ->route('projects.show', $project->id)
            ->with('success', 'تم إضافة المشروع بنجاح.');
    }

    /**
     * صفحة تعديل مشروع
     */
    public function edit($id)
    {
        abort_unless(auth()->user()->can('manage projects'), 403);

        $project = Project::findOrFail($id);
        $clients = User::whereHas('roles', function ($query) {
            $query->where('name', 'client');
        })->get();

        $managers = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['manager', 'admin', 'supervisor']);
        })->get();

        return view('projects.edit', compact('project', 'clients', 'managers'));
    }

    /**
     * تحديث بيانات المشروع
     */
    public function update(Request $request, $id)
    {
        abort_unless(auth()->user()->can('manage projects'), 403);

        $project = Project::findOrFail($id);

        // قيم افتراضية للتحقق
        $clientTypes = ['hospital', 'clinic', 'laboratory', 'pharmacy', 'government', 'company', 'other'];
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
            'code' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('projects', 'code')->ignore($project->id)
            ],

            // العميل
            'client_id' => 'nullable|exists:users,id',
            'client_name' => 'nullable|string|max:200',
            'client_type' => ['nullable', Rule::in($clientTypes)],

            // الوصف
            'description' => 'nullable|string',

            // الموقع
            'city' => 'nullable|string|max:100',
            'region' => ['nullable', Rule::in($regions)],
            'address' => 'nullable|string',

            // التواريخ
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'actual_end_date' => 'nullable|date|after_or_equal:start_date',

            // الإدارة
            'project_manager_id' => 'nullable|exists:users,id',
            'status' => ['required', Rule::in(['active', 'completed', 'on_hold', 'cancelled'])],
            'priority' => ['required', Rule::in(['low', 'medium', 'high'])],

            // المالية
            'budget' => 'nullable|numeric|min:0',
            'actual_cost' => 'nullable|numeric|min:0',

            // العقد
            'contract_number' => 'nullable|string|max:100',
            'contract_value' => 'nullable|numeric|min:0',
            'warranty_period' => 'nullable|integer|min:0',

            // ملاحظات
            'notes' => 'nullable|string',
        ]);

        // تحديث المشروع
        $project->update(array_merge($validated, [
            'is_active' => $validated['status'] === 'active',
        ]));

        return redirect()
            ->route('projects.show', $project->id)
            ->with('success', 'تم تحديث بيانات المشروع بنجاح.');
    }

    /**
     * عرض تفاصيل المشروع
     */
    public function show($id)
    {
        $project = Project::with([
            'client',
            'manager',
            'devices' => function ($query) {
                $query->with('assignedTechnician')->limit(10);
            },
            'pmPlans' => function ($query) {
                $query->with('device')->where('status', '!=', 'completed')->latest()->limit(5);
            },
            'breakdowns' => function ($query) {
                $query->with('device')->whereIn('status', ['open', 'assigned'])->latest()->limit(5);
            }
        ])->findOrFail($id);

        // إحصائيات المشروع
        $projectStats = $project->stats;

        // أجهزة المشروع حسب الحالة
        $devicesByStatus = [
            'active' => $project->devices()->where('status', 'active')->count(),
            'maintenance' => $project->devices()->where('status', 'under_maintenance')->count(),
            'inactive' => $project->devices()->where('status', 'inactive')->count(),
            'out_of_service' => $project->devices()->where('status', 'out_of_service')->count(),
        ];

        return view('projects.show', compact('project', 'projectStats', 'devicesByStatus'));
    }

    /**
     * حذف المشروع (Soft Delete)
     */
    public function destroy(Project $project)
    {
        abort_unless(auth()->user()->can('manage projects'), 403);

        // التحقق إذا كان هناك أجهزة مرتبطة بالمشروع
        if ($project->devices()->count() > 0) {
            return redirect()
                ->back()
                ->with('error', 'لا يمكن حذف المشروع لأنه يحتوي على أجهزة مرتبطة به.');
        }

        $project->delete();

        return redirect()
            ->route('projects.index')
            ->with('success', 'تم حذف المشروع بنجاح.');
    }

    /**
     * تحديث حالة المشروع
     */
    public function updateStatus(Request $request, Project $project)
    {
        abort_unless(auth()->user()->can('manage projects'), 403);

        $request->validate([
            'status' => ['required', Rule::in(['active', 'completed', 'on_hold', 'cancelled'])]
        ]);

        $project->update([
            'status' => $request->status,
            'is_active' => $request->status === 'active',
            'actual_end_date' => $request->status === 'completed' ? now() : $project->actual_end_date,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث حالة المشروع بنجاح.',
            'status' => $project->display_status['text'],
        ]);
    }

    /**
     * قائمة المشاريع المكتملة
     */
    public function completed(Request $request)
    {
        $query = Project::with(['client', 'manager'])
            ->where('status', 'completed');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('client_name', 'like', "%{$search}%");
            });
        }

        $projects = $query->latest()->paginate(15)->withQueryString();

        return view('projects.completed', compact('projects'));
    }

    /**
     * قائمة المشاريع المتأخرة
     */
    public function overdue(Request $request)
    {
        $query = Project::with(['client', 'manager'])
            ->where('status', 'active')
            ->whereDate('end_date', '<', now());

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $projects = $query->latest()->paginate(15)->withQueryString();

        return view('projects.overdue', compact('projects'));
    }

    /**
     * تحديث الميزانية
     */
    public function updateBudget(Request $request, Project $project)
    {
        abort_unless(auth()->user()->can('manage projects'), 403);

        $request->validate([
            'actual_cost' => 'nullable|numeric|min:0',
            'budget' => 'nullable|numeric|min:0',
        ]);

        $project->update($request->only(['actual_cost', 'budget']));

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الميزانية بنجاح.',
            'budget_usage' => $project->getBudgetUsagePercentage(),
            'is_within_budget' => $project->isWithinBudget(),
        ]);
    }

    /**
     * استعادة مشروع محذوف
     */
    public function restore(Project $project)
    {
        abort_unless(auth()->user()->can('manage projects'), 403);

        $project->restore();

        return redirect()
            ->back()
            ->with('success', 'تم استعادة المشروع بنجاح.');
    }
}