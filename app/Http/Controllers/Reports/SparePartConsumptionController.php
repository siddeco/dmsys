<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\SparePartUsage;
use App\Models\SparePart;
use App\Models\User;
use App\Models\Breakdown;
use Illuminate\Http\Request;

class SparePartConsumptionController extends Controller
{
    public function index(Request $request)
    {
        $query = SparePartUsage::with([
            'sparePart',
            'breakdown',
            'performer',
        ])->latest();

        /* ================= FILTERS ================= */

        // Spare Part
        if ($request->filled('spare_part_id')) {
            $query->where('spare_part_id', $request->spare_part_id);
        }

        // Type: issue / return
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Breakdown
        if ($request->filled('breakdown_id')) {
            $query->where('breakdown_id', $request->breakdown_id);
        }

        // Performed By
        if ($request->filled('performed_by')) {
            $query->where('performed_by', $request->performed_by);
        }

        // Date Range
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $usages = $query->paginate(20)->withQueryString();

        /* ================= FILTER DATA ================= */
        $spareParts = SparePart::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        $breakdowns = Breakdown::orderByDesc('id')->take(100)->get();

        return view('reports.spare-parts.consumption', compact(
            'usages',
            'spareParts',
            'users',
            'breakdowns'
        ));
    }
}
