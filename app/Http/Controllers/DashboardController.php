<?php

namespace App\Http\Controllers;

use App\Models\PmRecord;
use App\Models\PmPlan;
use App\Models\Device;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1) عدد تقارير PM لكل جهاز
        $pmPerDevice = Device::withCount('pmRecords')->get();

        // 2) حالات الصيانة
        $statusCounts = PmRecord::select('status', DB::raw('COUNT(*) as total'))
                                ->groupBy('status')
                                ->pluck('total', 'status');

        // 3) قائمة الصيانات القادمة خلال 30 يوم
        $upcomingPm = PmPlan::whereBetween('next_pm_date', [
                now(),
                now()->addDays(30)
            ])
            ->with('device')
            ->orderBy('next_pm_date', 'asc')
            ->get();

        return view('dashboard.index', compact('pmPerDevice', 'statusCounts', 'upcomingPm'));
    }
}
