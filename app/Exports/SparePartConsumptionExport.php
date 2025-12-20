<?php

namespace App\Exports;

use App\Models\SparePartUsage;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SparePartConsumptionExport implements FromView
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function view(): View
    {
        $query = SparePartUsage::with([
            'sparePart',
            'breakdown',
            'performer'
        ])->latest();

        if (!empty($this->filters['spare_part_id'])) {
            $query->where('spare_part_id', $this->filters['spare_part_id']);
        }

        if (!empty($this->filters['type'])) {
            $query->where('type', $this->filters['type']);
        }

        if (!empty($this->filters['breakdown_id'])) {
            $query->where('breakdown_id', $this->filters['breakdown_id']);
        }

        if (!empty($this->filters['performed_by'])) {
            $query->where('performed_by', $this->filters['performed_by']);
        }

        if (!empty($this->filters['from_date'])) {
            $query->whereDate('created_at', '>=', $this->filters['from_date']);
        }

        if (!empty($this->filters['to_date'])) {
            $query->whereDate('created_at', '<=', $this->filters['to_date']);
        }

        return view('reports.spare-parts.exports.excel', [
            'usages' => $query->get()
        ]);
    }
}
