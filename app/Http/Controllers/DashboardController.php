<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Breakdown;
use App\Models\PmPlan;
use App\Models\PmRecord;
use App\Models\SparePart;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
{
    $user = auth()->user();

    // =========================
    // TECHNICIAN DASHBOARD
    // =========================
    if ($user->hasRole('technician')) {

        $myBreakdowns = Breakdown::where('assigned_to', $user->id)->count();

        $myPmPlans = PmPlan::where('assigned_to', $user->id)->count();

        $latestBreakdowns = Breakdown::with('device')
            ->where('assigned_to', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', [
            'mode' => 'technician',
            'myBreakdowns' => $myBreakdowns,
            'myPmPlans' => $myPmPlans,
            'latestBreakdowns' => $latestBreakdowns,
        ]);
    }

    // =========================
    // ADMIN / ENGINEER DASHBOARD
    // =========================

    $totalDevices = Device::count();

    $openBreakdowns = Breakdown::where('status', 'open')->count();

    $inProgressBreakdowns = Breakdown::where('status', 'in_progress')->count();

    $pmDueSoon = PmPlan::whereBetween(
        'next_pm_date',
        [now(), now()->addDays(30)]
    )->count();

    $lowStockParts = SparePart::whereColumn('quantity', '<=', 'alert_threshold')->count();

    // Alerts
    $criticalBreakdowns = Breakdown::where('status', 'open')
        ->whereDate('created_at', '<=', now()->subDays(7))
        ->count();

    $overduePm = PmPlan::where('next_pm_date', '<', now())->count();

    $outOfStockParts = SparePart::where('quantity', 0)->count();

    // Charts
    $breakdownsChart = Breakdown::selectRaw('status, COUNT(*) as total')
        ->groupBy('status')
        ->pluck('total', 'status');

    $devicesChart = Device::selectRaw('status, COUNT(*) as total')
        ->groupBy('status')
        ->pluck('total', 'status');

    $pmSoonCount = $pmDueSoon;
    $pmLaterCount = PmPlan::where('next_pm_date', '>', now()->addDays(30))->count();

    $latestOpenBreakdowns = Breakdown::with(['device', 'project'])
    ->where('status', 'open')
    ->latest()
    ->take(5)
    ->get();


    $pmThisWeek = PmPlan::with('device')
    ->whereBetween('next_pm_date', [
        Carbon::now()->startOfWeek(),
        Carbon::now()->endOfWeek()
    ])
    ->where('status', '!=', 'done')
    ->orderBy('next_pm_date')
    ->take(5)
    ->get();

    return view('dashboard.index', compact(
    'totalDevices',
    'openBreakdowns',
    'inProgressBreakdowns',
    'pmDueSoon',
    'lowStockParts',
    'criticalBreakdowns',
    'overduePm',
    'outOfStockParts',
    'breakdownsChart',
    'devicesChart',
    'pmSoonCount',
    'pmLaterCount',
    'latestOpenBreakdowns',
    'pmThisWeek'
))->with('mode', 'admin');

}

}
