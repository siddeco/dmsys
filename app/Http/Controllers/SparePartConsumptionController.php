<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\SparePartTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SparePartConsumptionController extends Controller
{
    /**
     * عرض تقرير الاستهلاك
     */
    public function index(Request $request)
    {
        $query = SparePartTransaction::with(['sparePart', 'user', 'breakdown'])
            ->where('type', 'out')
            ->select('*');

        // فلترة حسب التاريخ
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // إذا لم يكن هناك تاريخ محدد، افترض آخر 30 يوم
        if (!$request->filled('start_date') && !$request->filled('end_date')) {
            $query->whereDate('created_at', '>=', now()->subDays(30));
        }

        // فلترة حسب القطعة
        if ($request->filled('spare_part_id')) {
            $query->where('spare_part_id', $request->spare_part_id);
        }

        // فلترة حسب العطل
        if ($request->filled('breakdown_id')) {
            $query->where('breakdown_id', $request->breakdown_id);
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(20);

        // إحصائيات الاستهلاك
        $consumptionStats = SparePartTransaction::where('type', 'out')
            ->select(
                DB::raw('COUNT(*) as total_transactions'),
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(quantity * unit_price) as total_value')
            )
            ->when($request->filled('start_date'), function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->start_date);
            })
            ->when($request->filled('end_date'), function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->end_date);
            })
            ->first();

        // الاستهلاك الشهري (لآخر 6 أشهر)
        $monthlyConsumption = SparePartTransaction::where('type', 'out')
            ->whereDate('created_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('COUNT(*) as transaction_count')
            )
            ->groupBy(DB::raw('YEAR(created_at), MONTH(created_at)'))
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // القطع الأكثر استهلاكاً
        $topConsumedParts = SparePartTransaction::where('type', 'out')
            ->with('sparePart')
            ->select(
                'spare_part_id',
                DB::raw('SUM(quantity) as total_consumed')
            )
            ->groupBy('spare_part_id')
            ->orderBy('total_consumed', 'desc')
            ->take(10)
            ->get();

        $spareParts = \App\Models\SparePart::all();
        $breakdowns = \App\Models\Breakdown::all();

        return view('reports.spare-parts.consumption', compact(
            'transactions',
            'consumptionStats',
            'monthlyConsumption',
            'topConsumedParts',
            'spareParts',
            'breakdowns'
        ));
    }
}