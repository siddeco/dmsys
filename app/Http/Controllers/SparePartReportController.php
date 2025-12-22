<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\SparePart;
use App\Models\SparePartTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class SparePartReportController extends Controller
{
    /**
     * عرض تقارير قطع الغيار الرئيسية
     */
    public function index(Request $request)
    {
        $query = SparePart::with(['device'])
            ->select('spare_parts.*')
            ->withCount([
                'transactions as total_in' => function ($q) {
                    $q->where('type', 'in');
                }
            ])
            ->withCount([
                'transactions as total_out' => function ($q) {
                    $q->where('type', 'out');
                }
            ]);

        // فلترة حسب المخزون
        if ($request->filled('stock_status')) {
            if ($request->stock_status == 'low') {
                $query->whereColumn('quantity', '<=', 'alert_threshold')
                    ->where('quantity', '>', 0);
            } elseif ($request->stock_status == 'out') {
                $query->where('quantity', 0);
            } elseif ($request->stock_status == 'normal') {
                $query->whereColumn('quantity', '>', 'alert_threshold');
            }
        }

        // فلترة حسب الجهاز
        if ($request->filled('device_id')) {
            $query->where('device_id', $request->device_id);
        }

        $parts = $query->orderBy('name')->paginate(20);

        // إحصائيات عامة
        $stats = [
            'total_parts' => SparePart::count(),
            'total_value' => SparePart::sum(DB::raw('quantity * unit_price')),
            'low_stock_parts' => SparePart::whereColumn('quantity', '<=', 'alert_threshold')
                ->where('quantity', '>', 0)->count(),
            'out_of_stock_parts' => SparePart::where('quantity', 0)->count(),
        ];

        $devices = \App\Models\Device::all();

        return view('reports.spare-parts.index', compact('parts', 'stats', 'devices'));
    }

    /**
     * تصدير إلى Excel
     */
    public function exportExcel(Request $request)
    {
        $parts = SparePart::with(['device'])
            ->withCount([
                'transactions as total_in' => function ($q) {
                    $q->where('type', 'in');
                }
            ])
            ->withCount([
                'transactions as total_out' => function ($q) {
                    $q->where('type', 'out');
                }
            ])
            ->get();

        return Excel::download(new class ($parts) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
            private $parts;

            public function __construct($parts)
            {
                $this->parts = $parts;
            }

            public function collection()
            {
                return $this->parts->map(function ($part) {
                    return [
                        'الاسم' => $part->name,
                        'رقم القطعة' => $part->part_number,
                        'الجهاز' => $part->device?->name ?? 'عام',
                        'المخزون الحالي' => $part->quantity,
                        'حد التنبيه' => $part->alert_threshold,
                        'الدخول' => $part->total_in,
                        'الخروج' => $part->total_out,
                        'الحالة' => $part->quantity == 0 ? 'نفذ' :
                            ($part->quantity <= $part->alert_threshold ? 'منخفض' : 'طبيعي'),
                    ];
                });
            }

            public function headings(): array
            {
                return [
                    'الاسم',
                    'رقم القطعة',
                    'الجهاز',
                    'المخزون الحالي',
                    'حد التنبيه',
                    'الدخول',
                    'الخروج',
                    'الحالة',
                ];
            }
        }, 'spare-parts-report-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * تصدير إلى PDF
     */
    public function exportPdf(Request $request)
    {
        $parts = SparePart::with(['device'])
            ->withCount([
                'transactions as total_in' => function ($q) {
                    $q->where('type', 'in');
                }
            ])
            ->withCount([
                'transactions as total_out' => function ($q) {
                    $q->where('type', 'out');
                }
            ])
            ->get();

        $pdf = PDF::loadView('reports.spare-parts.pdf', compact('parts'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('spare-parts-report-' . date('Y-m-d') . '.pdf');
    }
}