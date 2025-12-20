@extends('layouts.app')

@section('title', 'Breakdown Details')

@section('content')
    <div class="container-fluid">
        {{-- ================= HEADER ================= --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1">
                    <i class="fas fa-bolt text-danger me-2"></i>
                    Breakdown #{{ $breakdown->id }}
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('breakdowns.index') }}">Breakdowns</a></li>
                        <li class="breadcrumb-item active">#{{ $breakdown->id }}</li>
                    </ol>
                </nav>
            </div>

            <div class="btn-group">
                <a href="{{ route('breakdowns.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to List
                </a>
                @can('edit breakdowns')
                    <a href="{{ route('breakdowns.edit', $breakdown) }}" class="btn btn-outline-warning">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                @endcan
            </div>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle me-2"></i>
                    <div class="flex-grow-1">{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        {{-- ================= STATUS & PRIORITY BADGES ================= --}}
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="d-flex flex-wrap gap-2">
                    @php
                        $statusColors = [
                            'open' => 'danger',
                            'assigned' => 'warning',
                            'in_progress' => 'info',
                            'resolved' => 'primary',
                            'closed' => 'success'
                        ];
                        
                        $priorityColors = [
                            'high' => 'danger',
                            'medium' => 'warning',
                            'low' => 'success'
                        ];
                        
                        $statusColor = $statusColors[$breakdown->status] ?? 'secondary';
                        $priorityColor = $priorityColors[$breakdown->priority] ?? 'secondary';
                    @endphp

                    <span class="badge bg-{{ $statusColor }} px-3 py-2 fs-6">
                        <i class="fas fa-{{ $statusColor == 'danger' ? 'exclamation-triangle' : 
                                         ($statusColor == 'warning' ? 'user-clock' : 
                                         ($statusColor == 'info' ? 'spinner' : 
                                         ($statusColor == 'primary' ? 'check-circle' : 'check'))) }} me-1"></i>
                        {{ ucwords(str_replace('_', ' ', $breakdown->status)) }}
                    </span>

                    <span class="badge bg-{{ $priorityColor }} bg-opacity-10 text-{{ $priorityColor }} border border-{{ $priorityColor }} px-3 py-2 fs-6">
                        <i class="fas fa-{{ $priorityColor == 'danger' ? 'fire' : 
                                         ($priorityColor == 'warning' ? 'exclamation' : 'arrow-down') }} me-1"></i>
                        {{ ucfirst($breakdown->priority) }} Priority
                    </span>

                    @if($breakdown->pm_record_id)
                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-3 py-2 fs-6">
                            <i class="fas fa-wrench me-1"></i> From Preventive Maintenance
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="text-muted small">
                    Created: {{ $breakdown->created_at->format('M d, Y H:i') }}
                    @if($breakdown->updated_at != $breakdown->created_at)
                        <br>Last Updated: {{ $breakdown->updated_at->format('M d, Y H:i') }}
                    @endif
                </div>
            </div>
        </div>

        {{-- ================= MAIN CONTENT LAYOUT ================= --}}
        <div class="row">
            {{-- LEFT COLUMN: Breakdown Details & Workflow --}}
            <div class="col-lg-8">
                {{-- BREAKDOWN INFORMATION CARD --}}
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle text-primary me-2"></i>
                                Breakdown Information
                            </h5>
                            <span class="badge bg-light text-dark">
                                ID: #{{ $breakdown->id }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row g-4">
                            {{-- Device Info --}}
                            <div class="col-md-6">
                                <div class="border rounded p-3 h-100">
                                    <h6 class="text-muted mb-3">
                                        <i class="fas fa-desktop me-1"></i> Device Information
                                    </h6>
                                    <div class="mb-2">
                                        <small class="text-muted d-block">Device Name</small>
                                        <strong class="fs-5">{{ $breakdown->device->name }}</strong>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted d-block">Serial Number</small>
                                        <code>{{ $breakdown->device->serial_number }}</code>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted d-block">Location</small>
                                        <span class="badge bg-light text-dark">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            {{ $breakdown->device->location ?? 'Not Specified' }}
                                        </span>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Manufacturer</small>
                                        {{ $breakdown->device->manufacturer ?? '—' }}
                                    </div>
                                </div>
                            </div>

                            {{-- Project & Assignment --}}
                            <div class="col-md-6">
                                <div class="border rounded p-3 h-100">
                                    <h6 class="text-muted mb-3">
                                        <i class="fas fa-project-diagram me-1"></i> Project & Assignment
                                    </h6>
                                    <div class="mb-3">
                                        <small class="text-muted d-block">Project</small>
                                        @if($breakdown->project)
                                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                                                <i class="fas fa-folder me-1"></i>
                                                {{ $breakdown->project->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">No Project Assigned</span>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted d-block">Assigned Technician</small>
                                        @if($breakdown->assignedUser)
                                            <div class="d-flex align-items-center mt-1">
                                                <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    <i class="fas fa-user text-primary"></i>
                                                </div>
                                                <div>
                                                    <strong>{{ $breakdown->assignedUser->name }}</strong>
                                                    <div class="text-muted small">
                                                        {{ $breakdown->assignedUser->email }}
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-warning">
                                                <i class="fas fa-user-times me-1"></i> Not Assigned
                                            </span>
                                        @endif
                                    </div>

                                    @if($breakdown->engineer_report)
                                        <div>
                                            <small class="text-muted d-block">Service Report</small>
                                            <a href="{{ asset('storage/' . $breakdown->engineer_report) }}" 
                                               target="_blank"
                                               class="btn btn-outline-primary btn-sm mt-1">
                                                <i class="fas fa-file-pdf me-1"></i> View Report
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Description --}}
                            <div class="col-12">
                                <div class="border rounded p-3">
                                    <h6 class="text-muted mb-3">
                                        <i class="fas fa-align-left me-1"></i> Description
                                    </h6>
                                    <div class="bg-light p-3 rounded">
                                        {{ $breakdown->description }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- WORKFLOW ACTIONS CARD --}}
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-random text-warning me-2"></i>
                            Workflow Actions
                        </h5>
                    </div>

                    <div class="card-body">
                        {{-- OPEN → ASSIGN --}}
                        @if($breakdown->status === 'open')
                            <div class="alert alert-warning border-warning">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                                    <div class="flex-grow-1">
                                        <h6 class="alert-heading mb-1">Ticket is Open</h6>
                                        <p class="mb-0">Assign this breakdown to a technician to start the repair process.</p>
                                    </div>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('breakdowns.assign', $breakdown) }}" class="mt-3">
                                @csrf
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-8">
                                        <label class="form-label fw-medium">Select Technician</label>
                                        <select name="assigned_to" class="form-select" required>
                                            <option value="">Choose a technician...</option>
                                            @foreach($technicians as $tech)
                                                <option value="{{ $tech->id }}" 
                                                        {{ $breakdown->assigned_to == $tech->id ? 'selected' : '' }}>
                                                    {{ $tech->name }} ({{ $tech->role->name ?? 'Technician' }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <button class="btn btn-primary w-100 py-2">
                                            <i class="fas fa-user-check me-2"></i> Assign Ticket
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @endif

                        {{-- ASSIGNED → START --}}
                        @if($breakdown->status === 'assigned')
                            <div class="alert alert-info border-info">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-clock fa-2x me-3"></i>
                                    <div class="flex-grow-1">
                                        <h6 class="alert-heading mb-1">Assigned to {{ $breakdown->assignedUser->name ?? 'Technician' }}</h6>
                                        <p class="mb-0">Waiting for technician to start the repair work.</p>
                                    </div>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('breakdowns.start', $breakdown) }}" class="mt-3">
                                @csrf
                                <button class="btn btn-warning btn-lg w-100 py-3">
                                    <i class="fas fa-play-circle me-2"></i> Start Repair Work
                                </button>
                            </form>
                        @endif

                        {{-- IN PROGRESS → OPTIONS --}}
                        @if($breakdown->status === 'in_progress')
                            <div class="alert alert-info border-info">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-tools fa-2x me-3"></i>
                                    <div class="flex-grow-1">
                                        <h6 class="alert-heading mb-1">Repair in Progress</h6>
                                        <p class="mb-0">Technician is currently working on this breakdown.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <form method="POST" action="{{ route('breakdowns.resolve', $breakdown) }}">
                                        @csrf
                                        <button class="btn btn-success w-100 py-2" onclick="return confirm('Mark this breakdown as resolved?')">
                                            <i class="fas fa-check-circle me-2"></i> Mark as Resolved
                                        </button>
                                    </form>
                                </div>
                                <div class="col-md-6">
                                    <form method="POST" action="{{ route('breakdowns.close', $breakdown) }}">
                                        @csrf
                                        <button class="btn btn-outline-secondary w-100 py-2" onclick="return confirm('Close this breakdown ticket?')">
                                            <i class="fas fa-times-circle me-2"></i> Close Ticket
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif

                        {{-- STATUS MESSAGES --}}
                        @if($breakdown->status === 'resolved')
                            <div class="alert alert-success border-success">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle fa-2x me-3"></i>
                                    <div class="flex-grow-1">
                                        <h6 class="alert-heading mb-1">Resolved</h6>
                                        <p class="mb-0">This breakdown has been resolved successfully.</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($breakdown->status === 'closed')
                            <div class="alert alert-secondary border-secondary">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-archive fa-2x me-3"></i>
                                    <div class="flex-grow-1">
                                        <h6 class="alert-heading mb-1">Closed</h6>
                                        <p class="mb-0">This breakdown ticket has been closed.</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN: Spare Parts & Log --}}
            <div class="col-lg-4">
                {{-- SPARE PARTS ACTIONS (ONLY FOR IN_PROGRESS) --}}
                @can('manage spare parts')
                    @if($breakdown->status === 'in_progress')
                        <div class="card shadow-sm mb-4 border-0">
                            <div class="card-header bg-white border-0 py-3">
                                <h5 class="mb-0">
                                    <i class="fas fa-cogs text-danger me-2"></i>
                                    Spare Parts Management
                                </h5>
                            </div>

                            <div class="card-body">
                                {{-- ISSUE SPARE PART --}}
                                <div class="border rounded p-3 mb-3">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar-sm bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <i class="fas fa-arrow-up text-danger"></i>
                                        </div>
                                        <h6 class="mb-0 text-danger">Issue Spare Part</h6>
                                    </div>

                                    <form method="POST" action="{{ route('breakdowns.issue-part', $breakdown) }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label small fw-medium">Spare Part</label>
                                            <select name="issue_spare_part_id" class="form-select" required>
                                                <option value="">Select spare part...</option>
                                                @foreach($spareParts as $part)
                                                    <option value="{{ $part->id }}">
                                                        {{ $part->name }} 
                                                        <small class="text-muted">(Stock: {{ $part->quantity }})</small>
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small fw-medium">Quantity</label>
                                            <input type="number" name="issue_quantity" class="form-control" 
                                                   min="1" required placeholder="Enter quantity">
                                        </div>

                                        <button class="btn btn-danger w-100">
                                            <i class="fas fa-arrow-up me-2"></i> Issue Part
                                        </button>
                                    </form>
                                </div>

                                {{-- RETURN SPARE PART --}}
                                <div class="border rounded p-3">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar-sm bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <i class="fas fa-undo text-success"></i>
                                        </div>
                                        <h6 class="mb-0 text-success">Return to Stock</h6>
                                    </div>

                                    @if($returnableParts->isEmpty())
                                        <div class="text-center py-3">
                                            <i class="fas fa-box-open fa-2x text-muted mb-2"></i>
                                            <p class="text-muted small mb-0">No issued parts to return</p>
                                        </div>
                                    @else
                                        <form method="POST" action="{{ route('breakdowns.return-part', $breakdown) }}">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label small fw-medium">Spare Part</label>
                                                <select name="return_spare_part_id" class="form-select" required>
                                                    <option value="">Select part to return...</option>
                                                    @foreach($returnableParts as $part)
                                                        <option value="{{ $part->id }}">
                                                            {{ $part->name }} 
                                                            <small class="text-muted">(Remaining: {{ $part->remaining_qty }})</small>
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-medium">Quantity</label>
                                                <input type="number" name="return_quantity" class="form-control" 
                                                       min="1" required placeholder="Enter quantity">
                                            </div>

                                            <button class="btn btn-success w-100">
                                                <i class="fas fa-undo me-2"></i> Return to Stock
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @endcan

                {{-- SPARE PARTS LOG --}}
                @isset($spareUsages)
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-history text-info me-2"></i>
                                    Spare Parts Log
                                </h5>
                                <span class="badge bg-light text-dark">
                                    {{ $spareUsages->count() }} records
                                </span>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            @if($spareUsages->isEmpty())
                                <div class="text-center py-4">
                                    <i class="fas fa-clipboard-list fa-2x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">No spare part transactions</p>
                                </div>
                            @else
                                <div class="list-group list-group-flush">
                                    @foreach($spareUsages as $u)
                                        <div class="list-group-item border-0 py-3">
                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                <strong>{{ $u->sparePart?->name }}</strong>
                                                <span class="badge {{ $u->type === 'issue' ? 'bg-danger' : 'bg-success' }}">
                                                    {{ strtoupper($u->type) }}
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span class="badge bg-light text-dark">
                                                        Qty: {{ $u->quantity }}
                                                    </span>
                                                    <small class="text-muted ms-2">
                                                        {{ $u->created_at?->format('M d, H:i') }}
                                                    </small>
                                                </div>
                                                <small class="text-muted">
                                                    {{ $u->performer?->name ?? 'System' }}
                                                </small>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endisset
            </div>
        </div>

        {{-- ================= TIMELINE / ACTIVITY LOG (OPTIONAL) ================= --}}
        <div class="card shadow-sm mt-4 border-0">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0">
                    <i class="fas fa-stream text-secondary me-2"></i>
                    Activity Timeline
                </h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @php
                        $activities = [
                            ['icon' => 'plus-circle', 'color' => 'primary', 'text' => 'Breakdown reported', 'time' => $breakdown->created_at],
                        ];
                        
                        if($breakdown->assigned_at) {
                            $activities[] = ['icon' => 'user-check', 'color' => 'warning', 'text' => 'Assigned to technician', 'time' => $breakdown->assigned_at];
                        }
                        
                        if($breakdown->started_at) {
                            $activities[] = ['icon' => 'play-circle', 'color' => 'info', 'text' => 'Repair work started', 'time' => $breakdown->started_at];
                        }
                        
                        if($breakdown->resolved_at) {
                            $activities[] = ['icon' => 'check-circle', 'color' => 'success', 'text' => 'Marked as resolved', 'time' => $breakdown->resolved_at];
                        }
                        
                        if($breakdown->closed_at) {
                            $activities[] = ['icon' => 'archive', 'color' => 'secondary', 'text' => 'Ticket closed', 'time' => $breakdown->closed_at];
                        }
                        
                        usort($activities, function($a, $b) {
                            return $a['time'] <=> $b['time'];
                        });
                    @endphp

                    @foreach($activities as $activity)
                        <div class="timeline-item d-flex mb-3">
                            <div class="timeline-icon me-3">
                                <div class="avatar-sm bg-{{ $activity['color'] }} bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="fas fa-{{ $activity['icon'] }} text-{{ $activity['color'] }}"></i>
                                </div>
                            </div>
                            <div class="timeline-content flex-grow-1">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $activity['text'] }}</strong>
                                    <small class="text-muted">
                                        {{ $activity['time']->format('M d, Y H:i') }}
                                    </small>
                                </div>
                                @if(isset($activity['note']))
                                    <p class="mb-0 text-muted small">{{ $activity['note'] }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <style>
        .avatar-sm {
            width: 36px;
            height: 36px;
        }

        .timeline-item {
            position: relative;
        }

        .timeline-item:not(:last-child):after {
            content: '';
            position: absolute;
            left: 18px;
            top: 36px;
            bottom: -20px;
            width: 2px;
            background-color: #e9ecef;
        }

        .border-soft {
            border: 1px solid rgba(0,0,0,0.08) !important;
        }

        .card {
            border-radius: 12px;
        }

        .btn-group .btn {
            border-radius: 8px;
        }

        .list-group-item {
            border-left: 0;
            border-right: 0;
        }

        .list-group-item:first-child {
            border-top: 0;
        }

        .list-group-item:last-child {
            border-bottom: 0;
        }

        @media (max-width: 768px) {
            .btn-group {
                width: 100%;
            }
            
            .btn-group .btn {
                flex: 1;
            }
        }
    </style>
@endsection