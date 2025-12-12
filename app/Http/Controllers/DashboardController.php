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
        /* =======================
         | CARDS
         ======================= */
        $totalDevices = Device::count();

        $openBreakdowns = Breakdown::where('status', 'open')->count();

        $inProgressBreakdowns = Breakdown::where('status', 'in_progress')->count();

        $pmDueSoon = PmPlan::whereBetween(
            'next_pm_date',
            [now(), now()->addDays(30)]
        )->count();

        $lowStockParts = SparePart::whereColumn('quantity', '<=', 'alert_threshold')->count();


        /* =======================
         | ALERTS
         ======================= */

        // 🔴 Critical Breakdowns (open أكثر من 7 أيام)
        $criticalBreakdowns = Breakdown::where('status', 'open')
            ->where('created_at', '<=', now()->subDays(7))
            ->count();

        // 🟡 PM Overdue
        $overduePm = PmPlan::where('next_pm_date', '<', now())->count();

        // ⚫ Out of Stock
        $outOfStockParts = SparePart::where('quantity', '<=', 0)->count();


        /* =======================
         | CHARTS
         ======================= */

        $breakdownsChart = Breakdown::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $devicesChart = Device::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $pmSoonCount = PmPlan::whereBetween(
            'next_pm_date',
            [now(), now()->addDays(30)]
        )->count();

        $pmLaterCount = PmPlan::where('next_pm_date', '>', now()->addDays(30))->count();


        /* =======================
         | LATEST DATA
         ======================= */

        $latestBreakdowns = Breakdown::with('device')
            ->latest()
            ->take(5)
            ->get();


        /* =======================
         | RETURN VIEW
         ======================= */

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

            'latestBreakdowns'
        ));
    }
}
