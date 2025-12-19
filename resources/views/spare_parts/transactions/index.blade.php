@extends('layouts.admin')

@section('title', 'Spare Part Transactions')

@section('content')
    <div class="container-fluid">

        {{-- ================= HEADER ================= --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="mb-0">
                    <i class="fas fa-cogs me-1"></i>
                    Spare Part Transactions
                </h3>
                <small class="text-muted">
                    {{ $sparePart->name }} | Current Stock:
                    <strong>{{ $sparePart->quantity }}</strong>
                </small>
            </div>

            <a href="{{ route('spare_parts.index') }}" class="btn btn-outline-secondary btn-sm">
                ← Back to Spare Parts
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-1"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- ================= STOCK ACTIONS ================= --}}
        @can('manage spare parts')
            <div class="row mb-4">

                {{-- ADD STOCK --}}
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-success text-white">
                            <strong><i class="fas fa-plus-circle me-1"></i> Add Stock</strong>
                        </div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('spare_parts.transactions.in', $sparePart) }}">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">Quantity</label>
                                    <input type="number" name="quantity" class="form-control" min="1" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Notes (optional)</label>
                                    <textarea name="notes" class="form-control" rows="2"></textarea>
                                </div>

                                <button class="btn btn-success w-100">
                                    <i class="fas fa-arrow-down me-1"></i>
                                    Add to Stock
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- ISSUE STOCK --}}
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-danger text-white">
                            <strong><i class="fas fa-minus-circle me-1"></i> Issue Stock</strong>
                        </div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('spare_parts.transactions.out', $sparePart) }}">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">Quantity</label>
                                    <input type="number" name="quantity" class="form-control" min="1" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Related Breakdown (optional)</label>
                                    <input type="number" name="breakdown_id" class="form-control" placeholder="Breakdown ID">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Related PM Record (optional)</label>
                                    <input type="number" name="pm_record_id" class="form-control" placeholder="PM Record ID">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Notes (optional)</label>
                                    <textarea name="notes" class="form-control" rows="2"></textarea>
                                </div>

                                <button class="btn btn-danger w-100">
                                    <i class="fas fa-arrow-up me-1"></i>
                                    Issue Stock
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        @endcan

        {{-- ================= TRANSACTIONS TABLE ================= --}}
        <div class="card shadow-sm">
            <div class="card-header">
                <strong>
                    <i class="fas fa-exchange-alt me-1"></i>
                    Transactions History
                </strong>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Quantity</th>
                                <th>User</th>
                                <th>Linked To</th>
                                <th>Date</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($transactions as $tx)
                                <tr>
                                    <td>#{{ $tx->id }}</td>

                                    <td>
                                        @if($tx->type === 'in')
                                            <span class="badge bg-success">
                                                <i class="fas fa-arrow-down me-1"></i> IN
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-arrow-up me-1"></i> OUT
                                            </span>
                                        @endif
                                    </td>

                                    <td>{{ $tx->quantity }}</td>

                                    <td>{{ $tx->user->name ?? '-' }}</td>

                                    <td class="small">
                                        @if($tx->breakdown_id)
                                            Breakdown #{{ $tx->breakdown_id }}<br>
                                        @endif
                                        @if($tx->pm_record_id)
                                            PM Record #{{ $tx->pm_record_id }}
                                        @endif
                                        @if(!$tx->breakdown_id && !$tx->pm_record_id)
                                            —
                                        @endif
                                    </td>

                                    <td>{{ $tx->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        No transactions recorded.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>

            <div class="card-footer bg-white">
                {{ $transactions->links() }}
            </div>
        </div>

    </div>
@endsection