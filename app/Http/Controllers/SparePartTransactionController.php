<?php

namespace App\Http\Controllers;

use App\Models\SparePart;
use App\Models\SparePartTransaction;
use Illuminate\Http\Request;

class SparePartTransactionController extends Controller
{
    public function index(SparePart $sparePart)
    {
        abort_unless(auth()->user()->can('view spare parts'), 403);

        $transactions = $sparePart->transactions()
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('spare_parts.transactions.index', compact('sparePart', 'transactions'));
    }

    public function storeIn(Request $request, SparePart $sparePart)
    {
        abort_unless(auth()->user()->can('manage spare parts'), 403);

        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:1000',
        ]);

        SparePartTransaction::create([
            'spare_part_id' => $sparePart->id,
            'type' => 'in',
            'quantity' => $data['quantity'],
            'performed_by' => auth()->id(),
            'notes' => $data['notes'] ?? null,
        ]);

        $sparePart->increment('quantity', $data['quantity']);

        return back()->with('success', 'Stock added successfully.');
    }

    public function storeOut(Request $request, SparePart $sparePart)
    {
        abort_unless(auth()->user()->can('manage spare parts'), 403);

        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:1000',
            'breakdown_id' => 'nullable|exists:breakdowns,id',
            'pm_record_id' => 'nullable|exists:pm_records,id',
        ]);

        // منع الصرف إذا الكمية غير كافية
        if ($sparePart->quantity < $data['quantity']) {
            return back()->withErrors([
                'quantity' => 'Not enough stock available.'
            ]);
        }

        SparePartTransaction::create([
            'spare_part_id' => $sparePart->id,
            'type' => 'out',
            'quantity' => $data['quantity'],
            'breakdown_id' => $data['breakdown_id'] ?? null,
            'pm_record_id' => $data['pm_record_id'] ?? null,
            'performed_by' => auth()->id(),
            'notes' => $data['notes'] ?? null,
        ]);

        $sparePart->decrement('quantity', $data['quantity']);

        return back()->with('success', 'Stock issued successfully.');
    }
}
