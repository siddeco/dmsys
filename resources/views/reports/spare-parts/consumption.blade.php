@extends('layouts.admin')

@section('title', 'Spare Parts Consumption Report')

@section('content')
    <div class="container-fluid">

        {{-- ================= HEADER ================= --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-1">
                            <i class="fas fa-chart-line text-primary me-2"></i>
                            Spare Parts Consumption Report
                        </h3>
                        <p class="text-muted mb-0">
                            Track issue & return transactions for breakdowns and preventive maintenance
                        </p>
                    </div>
                    <div class="export-buttons">
                        <a href="{{ route('reports.spare-parts.consumption.export.excel', request()->query()) }}"
                            class="btn btn-success btn-sm">
                            <i class="fas fa-file-excel me-1"></i> Excel
                        </a>
                        <a href="{{ route('reports.spare-parts.consumption.export.pdf', request()->query()) }}"
                            class="btn btn-danger btn-sm">
                            <i class="fas fa-file-pdf me-1"></i> PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= FILTERS CARD ================= --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-light py-3">
                <h6 class="mb-0">
                    <i class="fas fa-filter text-secondary me-2"></i>
                    Filter Options
                </h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('reports.spare-parts.consumption') }}">

                    {{-- FILTER ROW 1 --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label fw-medium">Spare Part</label>
                            <select name="spare_part_id" class="form-select form-select-sm">
                                <option value="">All Parts</option>
                                @foreach($spareParts as $part)
                                    <option value="{{ $part->id }}" {{ request('spare_part_id') == $part->id ? 'selected' : '' }}>
                                        {{ $part->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-medium">Transaction Type</label>
                            <select name="type" class="form-select form-select-sm">
                                <option value="">All Types</option>
                                <option value="issue" {{ request('type') == 'issue' ? 'selected' : '' }}>Issue</option>
                                <option value="return" {{ request('type') == 'return' ? 'selected' : '' }}>Return</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-medium">Breakdown ID</label>
                            <select name="breakdown_id" class="form-select form-select-sm">
                                <option value="">All Breakdowns</option>
                                @foreach($breakdowns as $bd)
                                    <option value="{{ $bd->id }}" {{ request('breakdown_id') == $bd->id ? 'selected' : '' }}>
                                        #{{ $bd->id }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-medium">Performed By</label>
                            <select name="performed_by" class="form-select form-select-sm">
                                <option value="">All Users</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('performed_by') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- FILTER ROW 2 --}}
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label fw-medium">From Date</label>
                            <input type="date" name="from" class="form-control form-control-sm"
                                value="{{ request('from') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-medium">To Date</label>
                            <input type="date" name="to" class="form-control form-control-sm" value="{{ request('to') }}">
                        </div>

                        <div class="col-md-4 d-flex align-items-end">
                            <div class="btn-group" role="group">
                                <button type="submit" class="btn btn-primary btn-sm px-4">
                                    <i class="fas fa-search me-1"></i> Apply Filters
                                </button>
                                <a href="{{ route('reports.spare-parts.consumption') }}"
                                    class="btn btn-outline-secondary btn-sm px-4">
                                    <i class="fas fa-undo me-1"></i> Reset
                                </a>
                            </div>
                        </div>

                        <div class="col-md-4 text-md-end d-flex align-items-end justify-content-end">
                            <span class="text-muted small">
                                <i class="fas fa-info-circle me-1"></i>
                                Showing {{ $usages->total() }} records
                            </span>
                        </div>
                    </div>

                </form>
            </div>
        </div>

        {{-- ================= SUMMARY STATS ================= --}}
        @if(request()->anyFilled(['spare_part_id', 'type', 'breakdown_id', 'performed_by', 'from', 'to']))
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-info border-0 py-2">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle me-2"></i>
                            <small>
                                Filters applied:
                                @php
                                    $activeFilters = [];
                                    if (request('spare_part_id'))
                                        $activeFilters[] = 'Spare Part';
                                    if (request('type'))
                                        $activeFilters[] = 'Type';
                                    if (request('breakdown_id'))
                                        $activeFilters[] = 'Breakdown';
                                    if (request('performed_by'))
                                        $activeFilters[] = 'User';
                                    if (request('from') || request('to'))
                                        $activeFilters[] = 'Date Range';
                                @endphp
                                {{ implode(', ', $activeFilters) ?: 'None' }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- ================= DATA TABLE ================= --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Date & Time</th>
                                <th>Spare Part</th>
                                <th>Type</th>
                                <th class="text-center">Quantity</th>
                                <th>Breakdown</th>
                                <th class="pe-4">Performed By</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($usages as $u)
                                <tr class="border-bottom">
                                    <td class="ps-4">
                                        <div class="text-muted small">{{ $u->created_at->format('Y-m-d') }}</div>
                                        <div class="text-muted smaller">{{ $u->created_at->format('H:i') }}</div>
                                    </td>

                                    <td>
                                        <div class="fw-medium">{{ $u->sparePart->name ?? '-' }}</div>
                                        @if($u->sparePart)
                                            <div class="text-muted small">ID: {{ $u->sparePart->id }}</div>
                                        @endif
                                    </td>

                                    <td>
                                        <span
                                            class="badge rounded-pill {{ $u->type === 'issue' ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' }} px-3 py-2">
                                            <i class="fas fa-{{ $u->type === 'issue' ? 'arrow-up' : 'arrow-down' }} me-1"></i>
                                            {{ strtoupper($u->type) }}
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <span class="fw-bold fs-6 {{ $u->type === 'issue' ? 'text-danger' : 'text-success' }}">
                                            {{ $u->type === 'issue' ? '-' : '+' }}{{ $u->quantity }}
                                        </span>
                                    </td>

                                    <td>
                                        @if($u->breakdown)
                                            <a href="{{ route('breakdowns.show', $u->breakdown) }}"
                                                class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 text-decoration-none px-3 py-2">
                                                <i class="fas fa-wrench me-1"></i>
                                                #{{ $u->breakdown->id }}
                                            </a>
                                            <div class="text-muted small mt-1">
                                                {{ optional($u->breakdown)->title ? Str::limit($u->breakdown->title, 30) : 'N/A' }}
                                            </div>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>

                                    <td class="pe-4">
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="fas fa-user text-muted"></i>
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $u->performer->name ?? '-' }}</div>
                                                @if($u->performer)
                                                    <div class="text-muted small">{{ $u->performer->email ?? '' }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">No consumption records found</h5>
                                            <p class="text-muted small">
                                                Try adjusting your filters or add new transactions
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- PAGINATION --}}
            @if($usages->hasPages())
                <div class="card-footer bg-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Showing {{ $usages->firstItem() }} to {{ $usages->lastItem() }} of {{ $usages->total() }} entries
                        </div>
                        <div>
                            {{ $usages->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </div>

    <style>
        .avatar-sm {
            width: 32px;
            height: 32px;
            font-size: 0.875rem;
        }

        .card {
            border-radius: 10px;
            overflow: hidden;
        }

        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            padding: 1rem 1.5rem;
            border-bottom: 2px solid #e9ecef;
        }

        .table td {
            padding: 1rem 1.5rem;
            vertical-align: middle;
        }

        .badge {
            font-weight: 500;
        }

        .form-select-sm,
        .form-control-sm {
            border-radius: 6px;
            border: 1px solid #dee2e6;
        }

        .btn-group .btn {
            border-radius: 6px !important;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.04);
        }

        .border-bottom {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05) !important;
        }

        .smaller {
            font-size: 0.75rem;
        }
    </style>
@endsection