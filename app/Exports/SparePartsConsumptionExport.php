<?php

namespace App\Exports;

use App\Models\SparePartUsage;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SparePartsConsumptionExport implements FromView
{
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function view(): View
    {
        $query = SparePartUsage::with([
            'sparePart',
            'breakdown.project',
            'performer',
        ]);

        if (!empty($this->filters['from'])) {
            $query->whereDate('created_at', '>=', $this->filters['from']);
        }

        if (!empty($this->filters['to'])) {
            $query->whereDate('created_at', '<=', $this->filters['to']);
        }

        if (!empty($this->filters['project_id'])) {
            $query->whereHas('breakdown', function ($q) {
                $q->where('project_id', $this->filters['project_id']);
            });
        }

        if (!empty($this->filters['spare_part_id'])) {
            $query->where('spare_part_id', $this->filters['spare_part_id']);
        }

        $rows = $query->latest()->get();

        return view('reports.spare-parts.export', compact('rows'));
    }
}
