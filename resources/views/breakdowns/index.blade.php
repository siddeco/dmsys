<style>
    .breakdown-table td {
        vertical-align: middle;
    }

    .badge-soft {
        background-color: #f1f3f5;
        color: #495057;
        font-weight: 500;
    }

    .badge-from-pm {
        background: #e7f1ff;
        color: #0d6efd;
        border: 1px solid #cfe2ff;
    }

    .badge-status-open {
        background: #dc3545;
    }

    .badge-status-assigned {
        background: #6c757d;
    }

    .badge-status-progress {
        background: #ffc107;
        color: #212529;
    }

    .badge-status-resolved {
        background: #0d6efd;
    }

    .badge-status-closed {
        background: #198754;
    }

    .issue-text {
        max-width: 320px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>


@extends('layouts.admin')

@section('title', 'Breakdown Tickets')


@section('content')


    {{-- ================= HEADER ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0">
                <i class="fas fa-bolt me-1"></i> Breakdown Tickets
            </h3>
            <small class="text-muted">
                Reported device failures & corrective maintenance
            </small>
        </div>

        @can('manage breakdowns')
            <a href="{{ route('breakdowns.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i> New Breakdown
            </a>
        @endcan
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        </div>
    @endif

    {{-- ================= FILTERS ================= --}}
    <div class="filter-bar shadow-sm mb-4">

        <div class="card-body">

            <form method="GET" action="{{ route('breakdowns.index') }}">

                {{-- Row 1 --}}
                <div class="row g-3 mb-2">
                    <div class="col-md-4">
                        <label class="form-label">Search</label>
                        <input type="text" name="q" class="form-control" placeholder="Device / Serial / Description"
                            value="{{ request('q') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All</option>
                            @foreach(['open', 'assigned', 'in_progress', 'resolved', 'closed'] as $s)
                                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $s)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Project</label>
                        <select name="project_id" class="form-select">
                            <option value="">All</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Row 2 --}}
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Assigned To</label>
                        <select name="assigned_to" class="form-select">
                            <option value="">All</option>
                            @foreach($technicians as $tech)
                                <option value="{{ $tech->id }}" {{ request('assigned_to') == $tech->id ? 'selected' : '' }}>
                                    {{ $tech->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="from_pm" value="1" {{ request('from_pm') ? 'checked' : '' }}>
                        <label class="form-check-label">
                            From Preventive Maintenance
                        </label>
                    </div>


                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button class="btn btn-primary px-4">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>

                        <a href="{{ route('breakdowns.index') }}" class="btn btn-outline-secondary px-4">
                            Reset
                        </a>
                    </div>

                </div>

            </form>
        </div>
    </div>

    {{-- ================= TABLE ================= --}}
    <div class="table-responsive">
        <table class="table table-hover align-middle breakdown-table mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:70px">#</th>
                    <th>Device</th>
                    <th>Project</th>
                    <th>Issue</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th style="width:120px">Created</th>
                    <th style="width:80px" class="text-center">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($breakdowns as $bd)

                    @php
                        $statusMap = [
                            'open' => ['Open', 'badge-status-open'],
                            'assigned' => ['Assigned', 'badge-status-assigned'],
                            'in_progress' => ['In Progress', 'badge-status-progress'],
                            'resolved' => ['Resolved', 'badge-status-resolved'],
                            'closed' => ['Closed', 'badge-status-closed'],
                        ];
                        [$statusLabel, $statusClass] = $statusMap[$bd->status] ?? ['Unknown', 'badge-soft'];
                    @endphp

                    <tr>
                        {{-- ID --}}
                        <td class="text-muted fw-semibold">#{{ $bd->id }}</td>

                        {{-- Device --}}
                        <td>
                            <div class="fw-semibold">{{ $bd->device->name }}</div>
                            <div class="text-muted small">SN: {{ $bd->device->serial_number }}</div>

                            @if($bd->pm_record_id)
                                <span class="badge badge-from-pm mt-1">From PM</span>
                            @endif
                        </td>

                        {{-- Project --}}
                        <td>{{ $bd->project->name ?? '—' }}</td>

                        {{-- Issue --}}
                        <td>
                            <div class="issue-text" title="{{ $bd->description }}">
                                {{ $bd->description }}
                            </div>
                        </td>

                        {{-- Status --}}
                        <td>
                            <span class="badge {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>
                        </td>

                        {{-- Assigned --}}
                        <td>
                            {{ $bd->assignedUser->name ?? '—' }}
                        </td>

                        {{-- Created --}}
                        <td class="text-muted">
                            {{ $bd->created_at->format('Y-m-d') }}
                        </td>

                        {{-- Action --}}
                        <td class="text-center">
                            <a href="{{ route('breakdowns.show', $bd) }}" class="btn btn-sm btn-outline-primary"
                                title="View details">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            No breakdown tickets found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>


    <div class="card-footer bg-white">
        {{ $breakdowns->links() }}
    </div>
    </div>

    </div>
@endsection