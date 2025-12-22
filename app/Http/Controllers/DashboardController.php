<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Project;
use App\Models\PmPlan;
use App\Models\Breakdown;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        // ==================== الإحصائيات الرئيسية ====================
        $stats = [
            'total_devices' => Device::where('is_archived', false)->count(),
            'active_devices' => Device::where('is_archived', false)->where('status', 'active')->count(),
            'maintenance_devices' => Device::where('status', 'maintenance')->count(),
            'total_projects' => Project::count(),
            'pending_pm_plans' => PmPlan::where('status', 'pending')->count(),
            'open_breakdowns' => Breakdown::whereIn('status', ['reported', 'in_progress'])->count(),
            'total_technicians' => User::role('technician')->count(),
            'under_warranty_devices' => Device::where('warranty_expiry', '>', now())->count(),
        ];

        // ==================== أحدث الأجهزة مع التعامل مع الترجمة ====================
        $recent_devices = Device::with('project')
            ->where('is_archived', false)
            ->latest()
            ->take(8)
            ->get()
            ->map(function ($device) {
                // استخراج الاسم حسب اللغة
                $device->display_name = $this->extractDeviceName($device);
                return $device;
            });

        // ==================== أجهزة تحتاج معايرة ====================
        $devices_needing_calibration = Device::where('next_calibration_date', '<=', now()->addDays(30))
            ->where('is_archived', false)
            ->where('status', 'active')
            ->orderBy('next_calibration_date')
            ->take(6)
            ->get()
            ->map(function ($device) {
                $device->display_name = $this->extractDeviceName($device);
                return $device;
            });

        // ==================== أعطال مفتوحة ====================
        $open_breakdowns = Breakdown::with(['device', 'assignedTechnician'])
            ->whereIn('status', ['reported', 'in_progress'])
            ->latest()
            ->take(6)
            ->get();

        // ==================== توزيع الأجهزة ====================
        $devices_by_type = Device::where('is_archived', false)
            ->select('device_type', DB::raw('count(*) as count'))
            ->groupBy('device_type')
            ->get()
            ->pluck('count', 'device_type');

        $devices_by_status = Device::where('is_archived', false)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        return view('dashboard.index', compact(
            'stats',
            'recent_devices',
            'devices_needing_calibration',
            'open_breakdowns',
            'devices_by_type',
            'devices_by_status'
        ));
    }

    /**
     * استخراج اسم الجهاز من حقل الترجمة
     */
    private function extractDeviceName($device)
    {
        $name = $device->name;

        if (is_array($name)) {
            $locale = app()->getLocale();
            return $name[$locale] ?? $name['en'] ?? $name['ar'] ?? 'Unnamed Device';
        }

        if (is_string($name) && json_decode($name, true)) {
            $decoded = json_decode($name, true);
            if (is_array($decoded)) {
                $locale = app()->getLocale();
                return $decoded[$locale] ?? $decoded['en'] ?? $decoded['ar'] ?? 'Unnamed Device';
            }
        }

        return $name ?? 'Unnamed Device';
    }

    /**
     * إحصائيات جهاز معين (لـ AJAX)
     */
    public function getDeviceStats($id)
    {
        $device = Device::with([
            'breakdowns' => function ($query) {
                $query->where('status', 'completed');
            },
            'pmRecords' => function ($query) {
                $query->where('status', 'completed');
            }
        ])->findOrFail($id);

        return response()->json([
            'device_name' => $this->extractDeviceName($device),
            'total_downtime_hours' => $device->breakdowns->sum('downtime_hours'),
            'total_breakdowns' => $device->breakdowns->count(),
            'pm_completed_count' => $device->pmRecords->count(),
            'last_pm_date' => $device->pmRecords->last()->completed_date ?? 'N/A',
            'availability_rate' => $this->calculateAvailabilityRate($device),
            'status' => $device->status,
            'condition' => $device->condition,
            'next_calibration' => $device->next_calibration_date?->format('Y-m-d') ?? 'N/A',
        ]);
    }

    private function calculateAvailabilityRate($device)
    {
        $total_hours = 30 * 24;
        $downtime_hours = $device->breakdowns->sum('downtime_hours');

        if ($total_hours > 0) {
            return round((($total_hours - $downtime_hours) / $total_hours) * 100, 2);
        }

        return 100;
    }
}