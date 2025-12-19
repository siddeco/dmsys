@extends('layouts.admin')

@section('title', 'Spare Parts Consumption Report')

@section('content')
    <div class="container-fluid">

        <div class="d-flex align-items-center justify-content-between mb-3">
            <h3 class="mb-0">
                <i class="fas fa-chart-bar me-1"></i>
                Spare Parts Consumption Report
            </h3>
        </div>

        {{-- FILTERS --}}
        <form method="GET" action="{{ route('reports.spare-parts') }}" class="mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row g-3 align-items-end">

                        {{-- From --}}
                        <div class="col-md-3">
                            <label class="form-label">From Date</label>
                            <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                        </div>

                        {{-- To --}}
                        <div class="col-md-3">
                            <label class="form-label">To Date</label>
                            <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                        </div>

                        {{-- Project --}}
                        <div class="col-md-3">
                            <label class="form-label">Project</label>
                            <select name="project_id" class="form-select">
                                <option value="">All Projects</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Spare Part --}}
                        <div class="col-md-3">
                            <label class="form-label">Spare Part</label>
                            <select name="spare_part_id" class="form-select">
                                <option value="">All Parts</option>
                                @foreach($spareParts as $part)
                                    <option value="{{ $part->id }}" {{ request('spare_part_id') == $part->id ? 'selected' : '' }}>
                                        {{ $part->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Buttons --}}
                        <div class="col-12 d-flex gap-2 mt-2">
                            <button class="btn btn-primary">
                                <i class="fas fa-filter me-1"></i> Apply Filters
                            </button>

                            <a href="{{ route('reports.spare-parts') }}" class="btn btn-outline-secondary">
                                Reset
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </form>


        {{-- Summary --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <div class="text-muted">Issued</div>
                        <h4 class="text-danger">{{ $summary['issued'] }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <div class="text-muted">Returned</div>
                        <h4 class="text-success">{{ $summary['returned'] }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <div class="text-muted">Net</div>
                        <h4>{{ $summary['net'] }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <div class="text-muted">Transactions</div>
                        <h4>{{ $summary['transactions'] }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <a href="{{ route('reports.spare-parts.export', request()->query()) }}" class="btn btn-success">
            <i class="fas fa-file-excel me-1"></i>
            Export Excel
        </a>

        <a href="{{ route('reports.spare-parts.export.pdf', request()->query()) }}" class="btn btn-danger">
            <i class="fas fa-file-pdf me-1"></i> Export PDF
        </a>


        {{-- Table --}}
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Spare Part</th>
                            <th>Type</th>
                            <th>Qty</th>
                            <th>Project</th>
                            <th>By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usages as $u)
                            <tr>
                                <td>{{ $u->created_at?->format('Y-m-d') }}</td>
                                <td>{{ $u->sparePart?->name ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $u->type === 'issue' ? 'bg-danger' : 'bg-success' }}">
                                        {{ strtoupper($u->type) }}
                                    </span>
                                </td>
                                <td class="fw-bold">{{ $u->quantity }}</td>
                                <td>{{ $u->breakdown?->project?->name ?? '-' }}</td>
                                <td>{{ $u->performer?->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    No records found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection