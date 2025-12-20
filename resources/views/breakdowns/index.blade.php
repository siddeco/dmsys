@extends('layouts.app')

@section('title', 'Breakdown Tickets')

@section('content')
    <div class="dashboard-container">
        <div class="container-fluid px-0">
            <!-- Header Section -->
            <div class="row mb-4 mx-0">
                <div class="col-12 px-0">
                    <div class="card border-0 shadow-sm bg-gradient-primary">
                        <div class="card-body py-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="mb-1 text-white">
                                        <i class="fas fa-bolt me-2"></i>
                                        Breakdown Tickets
                                    </h4>
                                    <p class="mb-0 text-white-50">
                                        Reported device failures & corrective maintenance
                                    </p>
                                </div>
                                @can('manage breakdowns')
                                    <a href="{{ route('breakdowns.create') }}" class="btn btn-light btn-sm">
                                        <i class="fas fa-plus-circle me-1"></i> New Breakdown
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success Alert -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4 mx-3" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Filters Card -->
            <div class="card border-0 shadow-sm mb-4 mx-3">
                <div class="card-header bg-light py-3 border-0">
                    <h6 class="mb-0">
                        <i class="fas fa-filter text-primary me-2"></i>
                        Filter Tickets
                    </h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('breakdowns.index') }}" id="filterForm">
                        <!-- Row 1 -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Search</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" 
                                           name="q" 
                                           class="form-control" 
                                           placeholder="Device, Serial, Description..."
                                           value="{{ request('q') }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-medium">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    @foreach(['open', 'assigned', 'in_progress', 'resolved', 'closed'] as $s)
                                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $s)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-medium">Priority</label>
                                <select name="priority" class="form-select">
                                    <option value="">All Priorities</option>
                                    @foreach(['high', 'medium', 'low'] as $p)
                                        <option value="{{ $p }}" {{ request('priority') === $p ? 'selected' : '' }}>
                                            {{ ucfirst($p) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-medium">From PM</label>
                                <select name="from_pm" class="form-select">
                                    <option value="">All</option>
                                    <option value="1" {{ request('from_pm') == '1' ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ request('from_pm') == '0' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>

                        <!-- Row 2 -->
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-medium">Project</label>
                                <select name="project_id" class="form-select">
                                    <option value="">All Projects</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                            {{ $project->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-medium">Assigned To</label>
                                <select name="assigned_to" class="form-select">
                                    <option value="">All Technicians</option>
                                    <option value="me" {{ request('assigned_to') == 'me' ? 'selected' : '' }}>Assigned to Me</option>
                                    @foreach($technicians as $tech)
                                        <option value="{{ $tech->id }}" {{ request('assigned_to') == $tech->id ? 'selected' : '' }}>
                                            {{ $tech->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-medium">Date Range</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-calendar text-muted"></i>
                                    </span>
                                    <input type="date" 
                                           name="start_date" 
                                           class="form-control" 
                                           value="{{ request('start_date') }}"
                                           placeholder="From">
                                </div>
                            </div>

                            <div class="col-md-3 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary px-4 flex-grow-1">
                                    <i class="fas fa-filter me-1"></i> Apply Filters
                                </button>
                                <a href="{{ route('breakdowns.index') }}" class="btn btn-outline-secondary px-3">
                                    <i class="fas fa-redo"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Active Filters -->
                        @if(request()->anyFilled(['q', 'status', 'priority', 'project_id', 'assigned_to', 'from_pm', 'start_date']))
                            <div class="mt-3 pt-3 border-top">
                                <div class="d-flex align-items-center">
                                    <small class="fw-medium me-2">Active Filters:</small>
                                    <div class="d-flex flex-wrap gap-2">
                                        @if(request('q'))
                                            <span class="badge bg-primary bg-opacity-10 text-primary">
                                                Search: "{{ request('q') }}"
                                                <a href="{{ request()->fullUrlWithQuery(['q' => null]) }}" class="text-primary ms-1">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </span>
                                        @endif
                                        @if(request('status'))
                                            <span class="badge bg-info bg-opacity-10 text-info">
                                                Status: {{ ucfirst(request('status')) }}
                                                <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="text-info ms-1">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </span>
                                        @endif
                                        @if(request('priority'))
                                            <span class="badge bg-warning bg-opacity-10 text-warning">
                                                Priority: {{ ucfirst(request('priority')) }}
                                                <a href="{{ request()->fullUrlWithQuery(['priority' => null]) }}" class="text-warning ms-1">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </span>
                                        @endif
                                        @if(request('project_id'))
                                            @php
                                                $selectedProject = $projects->firstWhere('id', request('project_id'));
                                            @endphp
                                            <span class="badge bg-success bg-opacity-10 text-success">
                                                Project: {{ $selectedProject->name ?? 'Unknown' }}
                                                <a href="{{ request()->fullUrlWithQuery(['project_id' => null]) }}" class="text-success ms-1">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Stats Card -->
            <div class="row mb-4 mx-0">
                <div class="col-md-3 mb-3 px-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="text-uppercase text-muted mb-2">Total Tickets</h6>
                                    <h2 class="mb-0">{{ $breakdowns->total() }}</h2>
                                    <p class="text-muted mb-0 mt-2">
                                        <span class="badge bg-info bg-opacity-10 text-info">
                                            <i class="fas fa-bolt me-1"></i>
                                            All breakdowns
                                        </span>
                                    </p>
                                </div>
                                <div class="avatar-sm bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="fas fa-ticket-alt text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3 px-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="text-uppercase text-muted mb-2">Open</h6>
                                    <h2 class="mb-0 text-danger">{{ $openCount ?? 0 }}</h2>
                                    <p class="text-muted mb-0 mt-2">
                                        <span class="badge bg-danger bg-opacity-10 text-danger">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            Needs attention
                                        </span>
                                    </p>
                                </div>
                                <div class="avatar-sm bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="fas fa-exclamation-triangle text-danger"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3 px-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="text-uppercase text-muted mb-2">In Progress</h6>
                                    <h2 class="mb-0 text-warning">{{ $inProgressCount ?? 0 }}</h2>
                                    <p class="text-muted mb-0 mt-2">
                                        <span class="badge bg-warning bg-opacity-10 text-warning">
                                            <i class="fas fa-spinner me-1"></i>
                                            Being fixed
                                        </span>
                                    </p>
                                </div>
                                <div class="avatar-sm bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="fas fa-tools text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3 px-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="text-uppercase text-muted mb-2">Closed</h6>
                                    <h2 class="mb-0 text-success">{{ $closedCount ?? 0 }}</h2>
                                    <p class="text-muted mb-0 mt-2">
                                        <span class="badge bg-success bg-opacity-10 text-success">
                                            <i class="fas fa-check-circle me-1"></i>
                                            Resolved
                                        </span>
                                    </p>
                                </div>
                                <div class="avatar-sm bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="fas fa-check text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="card border-0 shadow-sm mx-3">
                <div class="card-header bg-light py-3 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="fas fa-list-alt text-primary me-2"></i>
                            All Breakdown Tickets
                        </h6>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-primary me-3">
                                {{ $breakdowns->total() }} Tickets
                            </span>
                            <!-- Items per page selector -->
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" 
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-list me-1"></i> {{ request('per_page', 10) }} per page
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['per_page' => 10]) }}">10 per page</a></li>
                                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['per_page' => 25]) }}">25 per page</a></li>
                                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['per_page' => 50]) }}">50 per page</a></li>
                                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['per_page' => 100]) }}">100 per page</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Ticket</th>
                                    <th>Device</th>
                                    <th>Project</th>
                                    <th>Issue</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Assigned To</th>
                                    <th class="pe-4 text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($breakdowns as $bd)
                                    @php
                                        $statusMap = [
                                            'open' => ['Open', 'danger'],
                                            'assigned' => ['Assigned', 'secondary'],
                                            'in_progress' => ['In Progress', 'warning'],
                                            'resolved' => ['Resolved', 'primary'],
                                            'closed' => ['Closed', 'success'],
                                        ];
                                        [$statusLabel, $statusColor] = $statusMap[$bd->status] ?? ['Unknown', 'secondary'];

                                        $priorityColors = [
                                            'high' => 'danger',
                                            'medium' => 'warning',
                                            'low' => 'info'
                                        ];
                                        $priorityColor = $priorityColors[$bd->priority] ?? 'secondary';
                                    @endphp

                                    <tr class="border-bottom {{ $bd->status === 'open' ? 'table-danger table-opacity-10' : '' }}">
                                        <!-- Ticket ID -->
                                        <td class="ps-4">
                                            <div class="fw-medium">#{{ $bd->id }}</div>
                                            <div class="text-muted small">
                                                {{ $bd->created_at->format('Y-m-d') }}
                                            </div>
                                            @if($bd->pm_record_id)
                                                <span class="badge bg-primary bg-opacity-10 text-primary small mt-1">
                                                    <i class="fas fa-wrench me-1"></i> From PM
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Device -->
                                        <td>
                                            <div class="fw-medium">{{ $bd->device->name }}</div>
                                            <div class="text-muted small">
                                                SN: {{ $bd->device->serial_number }}
                                            </div>
                                            <div class="text-muted smaller">
                                                {{ $bd->device->location ?? 'No location' }}
                                            </div>
                                        </td>

                                        <!-- Project -->
                                        <td>
                                            @if($bd->project)
                                                <span class="badge bg-info bg-opacity-10 text-info">
                                                    {{ $bd->project->name }}
                                                </span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>

                                        <!-- Issue -->
                                        <td>
                                            <div class="issue-text fw-medium" title="{{ $bd->description }}">
                                                {{ Str::limit($bd->description, 50) }}
                                            </div>
                                        </td>

                                        <!-- Priority -->
                                        <td>
                                            <span class="badge bg-{{ $priorityColor }} bg-opacity-10 text-{{ $priorityColor }}">
                                                <i class="fas fa-{{ $bd->priority === 'high' ? 'exclamation-triangle' : ($bd->priority === 'medium' ? 'exclamation' : 'info-circle') }} me-1"></i>
                                                {{ ucfirst($bd->priority) }}
                                            </span>
                                        </td>

                                        <!-- Status -->
                                        <td>
                                            <span class="badge bg-{{ $statusColor }} bg-opacity-10 text-{{ $statusColor }}">
                                                <i class="fas fa-{{ $statusColor === 'danger' ? 'exclamation' : ($statusColor === 'warning' ? 'spinner' : 'check') }}-circle me-1"></i>
                                                {{ $statusLabel }}
                                            </span>
                                        </td>

                                        <!-- Assigned To -->
                                        <td>
                                            @if($bd->assignedUser)
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                                                        <i class="fas fa-user text-primary small"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-medium">{{ $bd->assignedUser->name }}</div>
                                                        <div class="text-muted small">
                                                            {{ $bd->assignedUser->role->name ?? 'Technician' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">Unassigned</span>
                                            @endif
                                        </td>

                                        <!-- Actions -->
                                        <td class="pe-4 text-end">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('breakdowns.show', $bd) }}" 
                                                   class="btn btn-sm btn-outline-primary px-3"
                                                   title="View Details">
                                                    <i class="fas fa-eye me-1"></i> View
                                                </a>
                                                @can('edit breakdowns')
                                                    <a href="{{ route('breakdowns.edit', $bd) }}" 
                                                       class="btn btn-sm btn-outline-warning px-3"
                                                       title="Edit Ticket">
                                                        <i class="fas fa-edit me-1"></i> Edit
                                                    </a>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="py-4">
                                                <i class="fas fa-bolt fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">No breakdown tickets found</h5>
                                                <p class="text-muted small mb-0">
                                                    @can('manage breakdowns')
                                                        Start by reporting your first breakdown
                                                    @else
                                                        No breakdown tickets available
                                                    @endcan
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if($breakdowns->hasPages())
                    <div class="card-footer bg-transparent border-top-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Showing {{ $breakdowns->firstItem() }} to {{ $breakdowns->lastItem() }} of {{ $breakdowns->total() }} tickets
                            </div>
                            
                            <!-- Custom Pagination -->
                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-sm mb-0">
                                    <!-- Previous Page Link -->
                                    <li class="page-item {{ $breakdowns->onFirstPage() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $breakdowns->previousPageUrl() }}" 
                                           aria-label="Previous" {{ $breakdowns->onFirstPage() ? 'tabindex="-1"' : '' }}>
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>

                                    <!-- First Page -->
                                    @if($breakdowns->currentPage() > 3)
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $breakdowns->url(1) }}">1</a>
                                        </li>
                                        @if($breakdowns->currentPage() > 4)
                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>
                                        @endif
                                    @endif

                                    <!-- Page Numbers -->
                                    @foreach(range(1, $breakdowns->lastPage()) as $i)
                                        @if($i >= $breakdowns->currentPage() - 2 && $i <= $breakdowns->currentPage() + 2)
                                            <li class="page-item {{ $i == $breakdowns->currentPage() ? 'active' : '' }}">
                                                <a class="page-link" href="{{ $breakdowns->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endif
                                    @endforeach

                                    <!-- Last Page -->
                                    @if($breakdowns->currentPage() < $breakdowns->lastPage() - 2)
                                        @if($breakdowns->currentPage() < $breakdowns->lastPage() - 3)
                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>
                                        @endif
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $breakdowns->url($breakdowns->lastPage()) }}">
                                                {{ $breakdowns->lastPage() }}
                                            </a>
                                        </li>
                                    @endif

                                    <!-- Next Page Link -->
                                    <li class="page-item {{ !$breakdowns->hasMorePages() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $breakdowns->nextPageUrl() }}" 
                                           aria-label="Next" {{ !$breakdowns->hasMorePages() ? 'tabindex="-1"' : '' }}>
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                            
                            <!-- Jump to Page -->
                            <div class="d-flex align-items-center ms-3">
                                <span class="text-muted small me-2">Go to:</span>
                                <form method="GET" action="{{ route('breakdowns.index') }}" class="d-flex" id="jumpToPageForm">
                                    <input type="hidden" name="q" value="{{ request('q') }}">
                                    <input type="hidden" name="status" value="{{ request('status') }}">
                                    <input type="hidden" name="priority" value="{{ request('priority') }}">
                                    <input type="hidden" name="project_id" value="{{ request('project_id') }}">
                                    <input type="hidden" name="assigned_to" value="{{ request('assigned_to') }}">
                                    <input type="hidden" name="from_pm" value="{{ request('from_pm') }}">
                                    <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                                    <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                                    
                                    <input type="number" 
                                           name="page" 
                                           class="form-control form-control-sm" 
                                           style="width: 70px;"
                                           min="1" 
                                           max="{{ $breakdowns->lastPage() }}"
                                           value="{{ $breakdowns->currentPage() }}">
                                    <button type="submit" class="btn btn-sm btn-outline-primary ms-1">
                                        <i class="fas fa-arrow-right"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .dashboard-container .card {
            border-radius: 12px;
        }

        .dashboard-container .table-responsive {
            border-radius: 8px;
            overflow: hidden;
        }

        .table-danger.table-opacity-10 {
            background-color: rgba(220, 53, 69, 0.05) !important;
        }

        .issue-text {
            max-width: 250px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .avatar-sm {
            width: 32px;
            height: 32px;
        }

        .btn-group .btn {
            border-radius: 6px !important;
        }

        .filter-badges .badge {
            cursor: pointer;
            transition: all 0.2s;
        }

        .filter-badges .badge:hover {
            opacity: 0.8;
        }

        /* Pagination Styling */
        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .pagination .page-link {
            color: #495057;
            border: 1px solid #dee2e6;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        .pagination .page-link:hover {
            background-color: #e9ecef;
            color: #0d6efd;
        }

        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            background-color: #f8f9fa;
        }

        /* Jump to page input */
        #jumpToPageForm input[type="number"] {
            -moz-appearance: textfield;
        }

        #jumpToPageForm input[type="number"]::-webkit-outer-spin-button,
        #jumpToPageForm input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        @media (max-width: 768px) {
            .dashboard-container .table-responsive {
                margin-left: -12px;
                margin-right: -12px;
                width: calc(100% + 24px);
            }

            .issue-text {
                max-width: 150px;
            }

            .btn-group {
                flex-direction: column;
                gap: 0.5rem;
            }

            .btn-group .btn {
                width: 100%;
            }

            .card-footer .d-flex {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            /* Mobile pagination */
            .pagination {
                flex-wrap: wrap;
                justify-content: center;
            }

            .pagination .page-item {
                margin-bottom: 0.25rem;
            }

            /* Hide some pagination elements on mobile */
            .pagination .page-item:not(.active):not(:first-child):not(:last-child):not(:nth-child(2)):not(:nth-last-child(2)) {
                display: none;
            }

            .pagination .page-item.disabled span.page-link:contains("...") {
                display: none;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            const tooltips = document.querySelectorAll('[title]');
            tooltips.forEach(tooltip => {
                new bootstrap.Tooltip(tooltip);
            });

            // Fix table responsiveness on mobile
            function fixTableResponsive() {
                const tables = document.querySelectorAll('.table-responsive');
                tables.forEach(table => {
                    if (window.innerWidth < 768) {
                        table.style.overflowX = 'auto';
                        table.style.WebkitOverflowScrolling = 'touch';
                    }
                });
            }

            fixTableResponsive();
            window.addEventListener('resize', fixTableResponsive);

            // Auto-submit filters when some fields change
            const autoSubmitFields = ['status', 'priority', 'from_pm', 'project_id', 'assigned_to'];
            autoSubmitFields.forEach(field => {
                const element = document.querySelector(`[name="${field}"]`);
                if (element) {
                    element.addEventListener('change', function() {
                        document.getElementById('filterForm').submit();
                    });
                }
            });

            // Highlight open tickets
            const openTickets = document.querySelectorAll('.table-danger.table-opacity-10');
            openTickets.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = 'rgba(220, 53, 69, 0.1)';
                });

                row.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = 'rgba(220, 53, 69, 0.05)';
                });
            });

            // Quick status update (if needed in future)
            const statusBadges = document.querySelectorAll('.badge[class*="bg-opacity-10"]');
            statusBadges.forEach(badge => {
                badge.style.cursor = 'pointer';
                badge.addEventListener('click', function() {
                    const status = this.textContent.trim().toLowerCase();
                    const select = document.querySelector('select[name="status"]');
                    if (select) {
                        select.value = status;
                        document.getElementById('filterForm').submit();
                    }
                });
            });

            // Jump to page form validation
            const jumpToPageForm = document.getElementById('jumpToPageForm');
            if (jumpToPageForm) {
                const pageInput = jumpToPageForm.querySelector('input[name="page"]');
                if (pageInput) {
                    pageInput.addEventListener('change', function() {
                        const maxPage = parseInt(this.getAttribute('max'));
                        const minPage = parseInt(this.getAttribute('min'));
                        let value = parseInt(this.value);
                        
                        if (value < minPage) {
                            this.value = minPage;
                        } else if (value > maxPage) {
                            this.value = maxPage;
                        }
                    });
                }
            }

            // Items per page selector
            const perPageSelect = document.querySelector('select[name="per_page"]');
            if (perPageSelect) {
                perPageSelect.addEventListener('change', function() {
                    document.getElementById('filterForm').submit();
                });
            }
        });
    </script>
@endsection