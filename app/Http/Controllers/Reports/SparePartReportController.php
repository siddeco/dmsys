<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\SparePart;
use App\Models\SparePartUsage;
use Illuminate\Http\Request;
use App\Exports\SparePartsConsumptionExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class SparePartReportController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'from' => 'nullable|date',
            'to' => 'nullable|date|after_or_equal:from',
            'project_id' => 'nullable|exists:projects,id',
            'spare_part_id' => 'nullable|exists:spare_parts,id',
        ]);

        $query = SparePartUsage::with([
            'sparePart',
            'breakdown.project',
            'performer',
        ]);

        // Filters (سنفعّلها خطوة 2)
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        if ($request->filled('project_id')) {
            $query->whereHas('breakdown', function ($q) use ($request) {
                $q->where('project_id', $request->project_id);
            });
        }

        if ($request->filled('spare_part_id')) {
            $query->where('spare_part_id', $request->spare_part_id);
        }

        $usages = $query->latest()->get();

        // Summary
        $issued = $usages->where('type', 'issue')->sum('quantity');
        $returned = $usages->where('type', 'return')->sum('quantity');

        $summary = [
            'issued' => $issued,
            'returned' => $returned,
            'net' => $issued - $returned,
            'transactions' => $usages->count(),
        ];

        $projects = Project::select('id', 'name')->get();
        $spareParts = SparePart::select('id', 'name')->orderBy('name')->get();

        return view('reports.spare-parts.index', compact(
            'usages',
            'summary',
            'projects',
            'spareParts'
        ));
    }

    public function export(Request $request)
    {
        return Excel::download(
            new SparePartsConsumptionExport($request->all()),
            'spare_parts_consumption.xlsx'
        );
    }




    public function exportPdf(Request $request)
    {
        $query = SparePartUsage::with([
            'sparePart',
            'breakdown.project',
            'performer'
        ]);

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        if ($request->filled('project_id')) {
            $query->whereHas(
                'breakdown',
                fn($q) =>
                $q->where('project_id', $request->project_id)
            );
        }

        if ($request->filled('spare_part_id')) {
            $query->where('spare_part_id', $request->spare_part_id);
        }

        $rows = $query->latest()->get();

        $pdf = Pdf::loadView('reports.spare-parts.pdf', compact('rows'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('spare_parts_consumption.pdf');
    }

}
