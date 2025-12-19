@extends('layouts.admin')

@section('title', 'PM Plans')

@section('content')
    <div class="container-fluid">

        {{-- ================= HEADER ================= --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="mb-0">
                    <i class="fas fa-wrench me-1"></i>
                    Preventive Maintenance Plans
                </h3>
                <small class="text-muted">
                    Track PM schedules, overdue and due soon plans.
                </small>
            </div>

            @can('manage pm')
                <a href="{{ route('pm.plans.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-1"></i> Add PM Plan
                </a>
            @endcan
        </div>

        {{-- Flash --}}
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            </div>
        @endif

        {{-- ================= FILTER BAR ================= --}}
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <form method="GET" action="{{ route('pm.plans.index') }}">
                    <div class="row g-2 align-items-end">

                        <div class="col-md-3">
                            <label class="form-label">Search</label>
                            <input type="text" name="q" class="form-control"
                                   placeholder="Device name or SN"
                                   value="{{ request('q') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All</option>
                                @foreach(['pending', 'assigned', 'in_progress', 'completed'] as $s)
                                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $s)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label d-block">Timing</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="due_soon" value="1"
                                       {{ request('due_soon') ? 'checked' : '' }}>
                                <label class="form-check-label">Due Soon</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="overdue" value="1"
                                       {{ request('overdue') ? 'checked' : '' }}>
                                <label class="form-check-label">Overdue</label>
                            </div>
                        </div>

                        @can('manage pm')
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
                        @endcan

                        <div class="col-md-2 d-flex gap-2">
                            <button class="btn btn-primary w-100">
                                <i class="fas fa-filter"></i>
                            </button>
                            <a href="{{ route('pm.plans.index') }}" class="btn btn-outline-secondary w-100">
                                Reset
                            </a>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        {{-- ================= TABLE ================= --}}
        <div class="card shadow-sm">
            <form method="POST" action="{{ route('pm.plans.bulk') }}" id="bulkForm">
                @csrf

                <div class="d-flex gap-2 align-items-center mb-2">

                    <select name="action" class="form-select w-auto" required>
                        <option value="">Bulk Action</option>
                        <option value="assign">Assign Technician</option>
                        <option value="mark_done">Mark as Done</option>
                        <option value="delete">Delete</option>
                    </select>

                    {{-- يظهر فقط عند assign --}}
                    <select name="assigned_to" class="form-select w-auto d-none" id="bulkTech">
                        <option value="">Select technician</option>
                        @foreach($technicians as $tech)
                            <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                        @endforeach
                    </select>

                    <button class="btn btn-outline-primary">
                        Apply
                    </button>

                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px;" class="text-center">
                                <!-- <input type="checkbox" id="checkAll"> -->
                            </th>
                            <th>#</th>
                            <th>Device</th>
                            <th>Interval</th>
                            <th>Next PM</th>
                            <th>Last PM</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse($plans as $plan)
                                                                @php
    $next = $plan->next_pm_date;
    $isOverdue = $next && $next->lt(now()) && $plan->status !== 'completed';
    $isDueSoon = $next && !$isOverdue && $next->lte(now()->addDays(30)) && $plan->status !== 'completed';

    $timing = $isOverdue ? ['Overdue', 'danger']
        : ($isDueSoon ? ['Due Soon', 'warning']
            : ['On Track', 'success']);
                                                                @endphp

                                                                <tr>
                                                                    <td class="text-center">
                                                                        <input type="checkbox" class="row-check" name="ids[]" value="{{ $plan->id }}">
                                                                    </td>
                                                                    <td class="text-muted">#{{ $plan->id }}</td>

                                                                    <td>
                                                                        <strong>{{ $plan->device->name ?? 'N/A' }}</strong><br>
                                                                        <small class="text-muted">SN: {{ $plan->device->serial_number ?? '-' }}</small>
                                                                    </td>

                                                                    <td>{{ $plan->interval_months }} months</td>

                                                                    <td>{{ $next?->format('Y-m-d') ?? '-' }}</td>

                                                                    <td>{{ $plan->last_pm_date ?? '—' }}</td>

                                                                    <td>
                                                                        <span class="badge bg-secondary">
                                                                            {{ ucfirst(str_replace('_', ' ', $plan->status)) }}
                                                                        </span>

                                                                        <span class="badge bg-{{ $timing[1] }} ms-1">
                                                                            {{ $timing[0] }}
                                                                        </span>
                                                                    </td>

                                                                    <td class="text-center">
                                                                        <div class="d-flex justify-content-center gap-1">

                                                                            {{-- View --}}
                                                                            <a href="{{ route('pm.plans.show', $plan) }}"
                                                                               class="btn btn-sm btn-outline-primary"
                                                                               title="View">
                                                                                <i class="fas fa-eye"></i>
                                                                            </a>

                                                                            @can('manage pm')

                                                                                @if($plan->status === 'pending')
                                                                                    <button class="btn btn-sm btn-outline-info" title="Assign PM" data-bs-toggle="modal"
                                                                                        data-bs-target="#assignPm{{ $plan->id }}">
                                                                                        <i class="fas fa-user-plus"></i>
                                                                                    </button>
                                                                                @endif


                                                                                @if($plan->status === 'assigned')
                                                                                    <form method="POST" action="{{ route('pm.plans.start', $plan) }}">
                                                                                        @csrf
                                                                                        <button class="btn btn-sm btn-outline-success" title="Start">
                                                                                            <i class="fas fa-play"></i>
                                                                                        </button>
                                                                                    </form>
                                                                                @endif

                                                                                @if($plan->status === 'in_progress')
                                                                                    <a href="{{ route('pm.plans.show', $plan) }}#complete"
                                                                                       class="btn btn-sm btn-outline-danger"
                                                                                       title="Complete">
                                                                                        <i class="fas fa-check-circle"></i>
                                                                                    </a>
                                                                                @endif

                                                                                @if($plan->status !== 'completed')
                                                                                    <a href="{{ route('pm.plans.edit', $plan) }}"
                                                                                       class="btn btn-sm btn-outline-warning"
                                                                                       title="Edit">
                                                                                        <i class="fas fa-edit"></i>
                                                                                    </a>
                                                                                @endif

                                                                            @endcan
                                                                        </div>
                                                                    </td>
                                                                </tr>

                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                No PM Plans found.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>

                </table>
            </div>

            <div class="card-footer bg-white">
                {{ $plans->links() }}
            </div>
        </div>

    </div>

    @foreach($plans as $plan)
        @if($plan->status === 'pending')
            <div class="modal fade" id="assignPm{{ $plan->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                        <form method="POST" action="{{ route('pm.plans.assign', $plan) }}">
                            @csrf

                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-user-plus me-1"></i>
                                    Assign PM #{{ $plan->id }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">

                                <div class="mb-3">
                                    <label class="form-label">Technician</label>
                                    <select name="assigned_to" class="form-select" required>
                                        <option value="">Select technician</option>
                                        @foreach($technicians as $tech)
                                            <option value="{{ $tech->id }}">
                                                {{ $tech->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="alert alert-info small mb-0">
                                    <i class="fas fa-info-circle me-1"></i>
                                    The technician will be able to start PM immediately after assignment.
                                </div>

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    Cancel
                                </button>
                                <button class="btn btn-primary">
                                    Assign
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        @endif
    @endforeach

@endsection

<script>
    document.getElementById('checkAll')?.addEventListener('change', e => {
        document.querySelectorAll('.row-check')
            .forEach(c => c.checked = e.target.checked);
    });

    document.querySelector('[name="action"]').addEventListener('change', e => {
        document.getElementById('bulkTech')
            .classList.toggle('d-none', e.target.value !== 'assign');
    });
</script>

