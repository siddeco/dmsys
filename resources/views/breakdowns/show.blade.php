@extends('layouts.admin')

@section('title', 'Breakdown Details')

@section('content')
    <div class="container-fluid">

        {{-- ================= HEADER ================= --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">
                <i class="fas fa-bolt text-danger me-1"></i>
                Breakdown #{{ $breakdown->id }}
            </h3>

            <a href="{{ route('breakdowns.index') }}" class="btn btn-outline-secondary btn-sm">
                ← Back
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            </div>
        @endif


        {{-- ================= BREAKDOWN INFO ================= --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-light">
                <strong>Breakdown Information</strong>
            </div>

            <div class="card-body">
                <div class="row g-4">

                    <div class="col-md-4">
                        <div class="fw-semibold">Device</div>
                        {{ $breakdown->device->name }}
                        <div class="text-muted small">
                            SN: {{ $breakdown->device->serial_number }}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="fw-semibold">Project</div>
                        {{ $breakdown->project->name ?? '—' }}
                    </div>

                    <div class="col-md-4">
                        <div class="fw-semibold">Location</div>
                        {{ $breakdown->device->location ?? '-' }}
                    </div>

                    <div class="col-md-4">
                        <div class="fw-semibold">Status</div>
                        <span class="badge bg-dark px-3 py-2">
                            {{ strtoupper($breakdown->status) }}
                        </span>
                    </div>

                    <div class="col-md-4">
                        <div class="fw-semibold">Assigned To</div>
                        {{ $breakdown->assignedUser->name ?? 'Not Assigned' }}
                    </div>

                    @if($breakdown->pm_record_id)
                        <div class="col-md-4">
                            <div class="fw-semibold">Source</div>
                            <span class="badge bg-secondary">Generated from PM</span>
                        </div>
                    @endif

                    <div class="col-12">
                        <div class="fw-semibold">Description</div>
                        <p class="mb-0 mt-1 text-muted">
                            {{ $breakdown->description }}
                        </p>
                    </div>

                    @if($breakdown->engineer_report)
                        <div class="col-12">
                            <div class="fw-semibold">Service Report</div>
                            <a href="{{ asset('storage/' . $breakdown->engineer_report) }}" target="_blank"
                                class="btn btn-outline-primary btn-sm mt-2">
                                <i class="fas fa-file me-1"></i> View Report
                            </a>
                        </div>
                    @endif

                </div>
            </div>
        </div>


        {{-- ================= WORKFLOW ACTIONS ================= --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <strong><i class="fas fa-random me-1"></i> Workflow Actions</strong>
            </div>

            <div class="card-body">

                {{-- OPEN → ASSIGN --}}
                @if($breakdown->status === 'open')
                    <form method="POST" action="{{ route('breakdowns.assign', $breakdown) }}">
                        @csrf
                        <div class="row g-3 align-items-end">
                            <div class="col-md-6">
                                <label class="form-label">Assign Technician</label>
                                <select name="assigned_to" class="form-select" required>
                                    <option value="">Select Technician</option>
                                    @foreach($technicians as $tech)
                                        <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <button class="btn btn-primary w-100">
                                    <i class="fas fa-user-check me-1"></i> Assign
                                </button>
                            </div>
                        </div>
                    </form>
                @endif

                {{-- ASSIGNED → START --}}
                @if($breakdown->status === 'assigned')
                    <form method="POST" action="{{ route('breakdowns.start', $breakdown) }}">
                        @csrf
                        <button class="btn btn-warning">
                            <i class="fas fa-play me-1"></i> Start Work
                        </button>
                    </form>
                @endif

            </div>
        </div>


        {{-- ================= SPARE PARTS ================= --}}
        @can('manage spare parts')
            @if($breakdown->status === 'in_progress')
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <strong><i class="fas fa-cogs me-1"></i> Spare Parts</strong>
                    </div>

                    <div class="card-body">
                        <div class="row g-4">

                            {{-- ISSUE --}}
                            <div class="col-lg-6">
                                <div class="border rounded p-3 h-100">
                                    <div class="text-danger fw-bold mb-3">
                                        <i class="fas fa-arrow-up me-1"></i> Issue Spare Part
                                    </div>

                                    <form method="POST" action="{{ route('breakdowns.issue-part', $breakdown) }}">
                                        @csrf
                                        <div class="row g-3">
                                            <div class="col-md-8">
                                                <label class="form-label">Spare Part</label>
                                                <select name="issue_spare_part_id" class="form-select" required>
                                                    <option value="">Select spare part</option>
                                                    @foreach($spareParts as $part)
                                                        <option value="{{ $part->id }}">
                                                            {{ $part->name }} (Stock: {{ $part->quantity }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Qty</label>
                                                <input type="number" name="issue_quantity" class="form-control" min="1" required>
                                            </div>

                                            <div class="col-12">
                                                <button class="btn btn-danger w-100">
                                                    <i class="fas fa-arrow-up me-1"></i> Issue Part
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            {{-- RETURN --}}
                            <div class="col-lg-6">
                                <div class="border rounded p-3 h-100">
                                    <div class="text-success fw-bold mb-3">
                                        <i class="fas fa-undo me-1"></i> Return to Stock
                                    </div>

                                    <form method="POST" action="{{ route('breakdowns.return-part', $breakdown) }}">
                                        @csrf
                                        <div class="row g-3">
                                            <div class="col-md-8">
                                                <label class="form-label">Spare Part</label>
                                                <select name="return_spare_part_id" class="form-select" required>
                                                    <option value="">Select returnable part</option>
                                                    @foreach($returnableParts as $part)
                                                        <option value="{{ $part->id }}">
                                                            {{ $part->name }} (Remaining: {{ $part->remaining_qty }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Qty</label>
                                                <input type="number" name="return_quantity" class="form-control" min="1" required>
                                            </div>

                                            <div class="col-12">
                                                <button class="btn btn-success w-100">
                                                    <i class="fas fa-undo me-1"></i> Return to Stock
                                                </button>
                                            </div>
                                        </div>
                                    </form>

                                    @if($returnableParts->isEmpty())
                                        <div class="text-muted small mt-3">
                                            No issued parts to return for this breakdown.
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endif
        @endcan


        {{-- ================= SPARE PARTS LOG ================= --}}
        @isset($spareUsages)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <strong><i class="fas fa-list me-1"></i> Spare Parts Log</strong>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Part</th>
                                    <th>Type</th>
                                    <th>Qty</th>
                                    <th>By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($spareUsages as $u)
                                    <tr>
                                        <td class="text-muted">{{ $u->created_at?->format('Y-m-d H:i') }}</td>
                                        <td>{{ $u->sparePart?->name }}</td>
                                        <td>
                                            <span class="badge {{ $u->type === 'issue' ? 'bg-danger' : 'bg-success' }}">
                                                {{ strtoupper($u->type) }}
                                            </span>
                                        </td>
                                        <td class="fw-bold">{{ $u->quantity }}</td>
                                        <td>{{ $u->performer?->name }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">
                                            No spare part transactions yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endisset

    </div>
@endsection