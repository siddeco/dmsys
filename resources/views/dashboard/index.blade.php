@extends('layouts.app')

@section('content')
    <div class="dashboard-container">
        {{-- ================= TECHNICIAN DASHBOARD ================= --}}
        @if($mode === 'technician')
            <div class="container-fluid px-0">
                {{-- Welcome Header --}}
                <div class="row mb-4 mx-0">
                    <div class="col-12 px-0">
                        <div class="card border-0 shadow-sm bg-gradient-primary">
                            <div class="card-body py-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h4 class="mb-1 text-white">
                                            <i class="fas fa-user-hard-hat me-2"></i>
                                            Welcome, {{ auth()->user()->name }}
                                        </h4>
                                        <p class="mb-0 text-white-50">
                                            Here's your current workload and tasks
                                        </p>
                                    </div>
                                    <div
                                        class="avatar-lg bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="fas fa-tools fa-2x text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick Stats Cards --}}
                <div class="row mb-4 mx-0">
                    <div class="col-md-6 mb-3 px-3">
                        <div class="card border-0 shadow-sm h-100 hover-lift">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="text-uppercase text-muted mb-2">My Breakdown Tasks</h6>
                                        <h2 class="mb-0">{{ $myBreakdowns }}</h2>
                                        <p class="text-muted mb-0 mt-2">
                                            <span class="badge bg-danger bg-opacity-10 text-danger">
                                                <i class="fas fa-circle fa-xs me-1"></i>
                                                Assigned to you
                                            </span>
                                        </p>
                                    </div>
                                    <div
                                        class="avatar-sm bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="fas fa-bolt text-danger"></i>
                                    </div>
                                </div>
                                <a href="{{ route('breakdowns.index', ['assigned' => 'me']) }}" class="stretched-link"></a>
                            </div>
                            <div class="card-footer bg-transparent border-top-0 pt-0">
                                <small class="text-muted">
                                    <i class="fas fa-arrow-right me-1"></i>
                                    View all breakdowns
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3 px-3">
                        <div class="card border-0 shadow-sm h-100 hover-lift">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="text-uppercase text-muted mb-2">My PM Tasks</h6>
                                        <h2 class="mb-0">{{ $myPmPlans }}</h2>
                                        <p class="text-muted mb-0 mt-2">
                                            <span class="badge bg-primary bg-opacity-10 text-primary">
                                                <i class="fas fa-circle fa-xs me-1"></i>
                                                Preventive maintenance
                                            </span>
                                        </p>
                                    </div>
                                    <div
                                        class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="fas fa-wrench text-primary"></i>
                                    </div>
                                </div>
                                <a href="{{ route('pm.plans.index', ['assigned' => 'me']) }}" class="stretched-link"></a>
                            </div>
                            <div class="card-footer bg-transparent border-top-0 pt-0">
                                <small class="text-muted">
                                    <i class="fas fa-arrow-right me-1"></i>
                                    View all PM tasks
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Latest Breakdowns --}}
                <div class="card border-0 shadow-sm mx-3">
                    <div class="card-header bg-light py-3 border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="fas fa-clock text-warning me-2"></i>
                                My Latest Breakdowns
                            </h6>
                            <a href="{{ route('breakdowns.index', ['assigned' => 'me']) }}"
                                class="btn btn-sm btn-outline-secondary">
                                View All
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Device</th>
                                        <th>Reported</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th class="pe-4 text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($latestBreakdowns as $b)
                                                            <tr class="border-bottom">
                                                                <td class="ps-4">
                                                                    <div class="fw-medium">{{ $b->device->name['en'] ?? 'Device' }}</div>
                                                                    <div class="text-muted small">
                                                                        {{ $b->device->location ?? 'No location' }}
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="small">{{ $b->created_at->format('Y-m-d') }}</div>
                                                                    <div class="text-muted smaller">{{ $b->created_at->format('H:i') }}</div>
                                                                </td>
                                                                <td>
                                                                    @php
        $priorityColors = [
            'high' => 'danger',
            'medium' => 'warning',
            'low' => 'info'
        ];
                                                                    @endphp
                                         <span
                                                                        class="badge bg-{{ $priorityColors[$b->priority] ?? 'secondary' }} bg-opacity-10 text-{{ $priorityColors[$b->priority] ?? 'secondary' }}">
                                                                        {{ ucfirst($b->priority) }}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                                        {{ ucfirst($b->status) }}
                                                                    </span>
                                                                </td>
                                                                <td class="pe-4 text-end">
                                                                    <a href="{{ route('breakdowns.show', $b->id) }}"
                                                                        class="btn btn-sm btn-outline-primary px-3">
                                                                        <i class="fas fa-eye me-1"></i> View
                                                                    </a>
                                                                </td>
                                                            </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <div class="py-4">
                                                    <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>
                                                    <h5 class="text-muted">No assigned tasks</h5>
                                                    <p class="text-muted small mb-0">You're all caught up!</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================= ADMIN / ENGINEER DASHBOARD ================= --}}
        @else
            <div class="container-fluid px-0">
                {{-- Welcome Header --}}
                <div class="row mb-4 mx-0">
                    <div class="col-12 px-0">
                        <div class="card border-0 shadow-sm bg-gradient-primary">
                            <div class="card-body py-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h4 class="mb-1 text-white">
                                            <i class="fas fa-tachometer-alt me-2"></i>
                                            Maintenance Dashboard
                                        </h4>
                                        <p class="mb-0 text-white-50">
                                            Overview of maintenance operations
                                        </p>
                                    </div>
                                    <div class="text-white">
                                        <small>Last updated: {{ now()->format('Y-m-d H:i') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== KPI CARDS ===== --}}
                <div class="row mb-4 mx-0">
                    <div class="col-lg-3 col-md-6 mb-3 px-3">
                        <div class="card border-0 shadow-sm h-100 hover-lift">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="text-uppercase text-muted mb-2">Total Devices</h6>
                                        <h2 class="mb-0">{{ $totalDevices }}</h2>
                                        <p class="text-muted mb-0 mt-2">
                                            <span class="badge bg-info bg-opacity-10 text-info">
                                                <i class="fas fa-server me-1"></i>
                                                Active devices
                                            </span>
                                        </p>
                                    </div>
                                    <div
                                        class="avatar-sm bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="fas fa-tools text-info"></i>
                                    </div>
                                </div>
                                <a href="{{ route('devices.index') }}" class="stretched-link"></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3 px-3">
                        <div class="card border-0 shadow-sm h-100 hover-lift">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="text-uppercase text-muted mb-2">Open Breakdowns</h6>
                                        <h2 class="mb-0 text-danger">{{ $openBreakdowns }}</h2>
                                        <p class="text-muted mb-0 mt-2">
                                            <span class="badge bg-danger bg-opacity-10 text-danger">
                                                <i class="fas fa-exclamation-circle me-1"></i>
                                                Needs attention
                                            </span>
                                        </p>
                                    </div>
                                    <div
                                        class="avatar-sm bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="fas fa-exclamation-triangle text-danger"></i>
                                    </div>
                                </div>
                                <a href="{{ route('breakdowns.index', ['status' => 'open']) }}" class="stretched-link"></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3 px-3">
                        <div class="card border-0 shadow-sm h-100 hover-lift">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="text-uppercase text-muted mb-2">In Progress</h6>
                                        <h2 class="mb-0 text-warning">{{ $inProgressBreakdowns }}</h2>
                                        <p class="text-muted mb-0 mt-2">
                                            <span class="badge bg-warning bg-opacity-10 text-warning">
                                                <i class="fas fa-spinner me-1"></i>
                                                Being worked on
                                            </span>
                                        </p>
                                    </div>
                                    <div
                                        class="avatar-sm bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="fas fa-spinner text-warning"></i>
                                    </div>
                                </div>
                                <a href="{{ route('breakdowns.index', ['status' => 'in_progress']) }}"
                                    class="stretched-link"></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3 px-3">
                        <div class="card border-0 shadow-sm h-100 hover-lift">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="text-uppercase text-muted mb-2">PM Due (30 Days)</h6>
                                        <h2 class="mb-0 text-primary">{{ $pmDueSoon }}</h2>
                                        <p class="text-muted mb-0 mt-2">
                                            <span class="badge bg-primary bg-opacity-10 text-primary">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                Upcoming schedule
                                            </span>
                                        </p>
                                    </div>
                                    <div
                                        class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="fas fa-calendar-check text-primary"></i>
                                    </div>
                                </div>
                                <a href="{{ route('pm.plans.index', ['filter' => 'due_soon']) }}" class="stretched-link"></a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== SECONDARY METRICS ===== --}}
                <div class="row mb-4 mx-0">
                    <div class="col-md-3 mb-3 px-3">
                        <div class="card border-0 shadow-sm h-100 hover-lift">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="text-uppercase text-muted mb-2">Low Stock Parts</h6>
                                        <h2 class="mb-0">{{ $lowStockParts ?? 0 }}</h2>
                                        <p class="text-muted mb-0 mt-2">
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                <i class="fas fa-box me-1"></i>
                                                Needs restocking
                                            </span>
                                        </p>
                                    </div>
                                    <div
                                        class="avatar-sm bg-secondary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="fas fa-boxes text-secondary"></i>
                                    </div>
                                </div>
                                <a href="{{ route('spare_parts.index', ['low_stock' => 1]) }}" class="stretched-link"></a>
                            </div>
                        </div>
                    </div>

                    @if(isset($criticalBreakdowns) && $criticalBreakdowns > 0)
                        <div class="col-md-3 mb-3 px-3">
                            <div class="card border-danger border-2 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="text-uppercase text-danger mb-2">Critical SLA</h6>
                                            <h2 class="mb-0 text-danger">{{ $criticalBreakdowns }}</h2>
                                            <p class="text-danger mb-0 mt-2">
                                                <i class="fas fa-clock me-1"></i>
                                                Exceeded SLA
                                            </p>
                                        </div>
                                        <div
                                            class="avatar-sm bg-danger rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="fas fa-exclamation text-white"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(isset($overduePm) && $overduePm > 0)
                        <div class="col-md-3 mb-3 px-3">
                            <div class="card border-warning border-2 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="text-uppercase text-warning mb-2">PM Overdue</h6>
                                            <h2 class="mb-0 text-warning">{{ $overduePm }}</h2>
                                            <p class="text-warning mb-0 mt-2">
                                                <i class="fas fa-calendar-times me-1"></i>
                                                Past due date
                                            </p>
                                        </div>
                                        <div
                                            class="avatar-sm bg-warning rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="fas fa-clock text-white"></i>
                                        </div>
                                    </div>
                                    <a href="{{ route('pm.plans.index', ['overdue' => 1]) }}" class="stretched-link"></a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- ===== ALERTS ===== --}}
                @if((!empty($criticalBreakdowns) && $criticalBreakdowns > 0) || (isset($overduePm) && $overduePm > 0))
                    <div class="row mb-4 mx-0">
                        @if(!empty($criticalBreakdowns) && $criticalBreakdowns > 0)
                            <div class="col-md-6 mb-3 px-3">
                                <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="alert-heading mb-1">Critical SLA Alert</h6>
                                        <p class="mb-0">{{ $criticalBreakdowns }} breakdown(s) have exceeded SLA time.</p>
                                    </div>
                                    <a href="{{ route('breakdowns.index', ['status' => 'open']) }}"
                                        class="btn btn-sm btn-outline-danger ms-3">
                                        View Now
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if(isset($overduePm) && $overduePm > 0)
                            <div class="col-md-6 mb-3 px-3">
                                <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fas fa-calendar-times fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="alert-heading mb-1">PM Overdue</h6>
                                        <p class="mb-0">{{ $overduePm }} preventive maintenance task(s) are overdue.</p>
                                    </div>
                                    <a href="{{ route('pm.plans.index', ['overdue' => 1]) }}"
                                        class="btn btn-sm btn-outline-warning ms-3">
                                        View Overdue
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- ===== CHARTS ===== --}}
                <div class="row mb-4 mx-0">
                    <div class="col-md-4 mb-3 px-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-light py-3 border-0">
                                <h6 class="mb-0">
                                    <i class="fas fa-chart-pie text-primary me-2"></i>
                                    Breakdowns Status
                                </h6>
                            </div>
                            <div class="card-body d-flex align-items-center justify-content-center">
                                <div class="chart-container">
                                    <canvas id="breakdownsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3 px-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-light py-3 border-0">
                                <h6 class="mb-0">
                                    <i class="fas fa-chart-doughnut text-info me-2"></i>
                                    Devices Status
                                </h6>
                            </div>
                            <div class="card-body d-flex align-items-center justify-content-center">
                                <div class="chart-container">
                                    <canvas id="devicesChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3 px-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-light py-3 border-0">
                                <h6 class="mb-0">
                                    <i class="fas fa-chart-bar text-success me-2"></i>
                                    PM Schedule
                                </h6>
                            </div>
                            <div class="card-body d-flex align-items-center justify-content-center">
                                <div class="chart-container">
                                    <canvas id="pmChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== DASHBOARD TABLES ===== --}}
                <div class="row mx-0">
                    {{-- Latest Open Breakdowns --}}
                    <div class="col-lg-6 mb-3 px-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-light py-3 border-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        <i class="fas fa-exclamation-circle text-danger me-2"></i>
                                        Latest Open Breakdowns
                                    </h6>
                                    <a href="{{ route('breakdowns.index', ['status' => 'open']) }}"
                                        class="btn btn-sm btn-outline-danger">
                                        View All
                                    </a>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-4">Device</th>
                                                <th>Project</th>
                                                <th>Location</th>
                                                <th>Date</th>
                                                <th class="pe-4 text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($latestOpenBreakdowns as $b)
                                                <tr class="border-bottom">
                                                    <td class="ps-4">
                                                        <div class="fw-medium">{{ $b->device->name }}</div>
                                                        <div class="text-muted small">
                                                            ID: {{ $b->device->id }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info bg-opacity-10 text-info">
                                                            {{ $b->project->name ?? '-' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="text-muted small">{{ $b->device->location ?? '-' }}</div>
                                                    </td>
                                                    <td>
                                                        <div class="small">{{ $b->created_at->format('Y-m-d') }}</div>
                                                        <div class="text-muted smaller">{{ $b->created_at->format('H:i') }}</div>
                                                    </td>
                                                    <td class="pe-4 text-end">
                                                        <a href="{{ route('breakdowns.show', $b->id) }}"
                                                            class="btn btn-sm btn-outline-primary px-3">
                                                            <i class="fas fa-eye me-1"></i> View
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-5">
                                                        <div class="py-4">
                                                            <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>
                                                            <h5 class="text-muted">No open breakdowns</h5>
                                                            <p class="text-muted small mb-0">All systems operational</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- PM Plans This Week --}}
                    <div class="col-lg-6 mb-3 px-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-light py-3 border-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        <i class="fas fa-calendar-alt text-primary me-2"></i>
                                        PM Plans This Week
                                    </h6>
                                    <a href="{{ route('pm.plans.index') }}" class="btn btn-sm btn-outline-primary">
                                        View All
                                    </a>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-4">Device</th>
                                                <th>Next PM</th>
                                                <th>Status</th>
                                                <th class="pe-4 text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($pmThisWeek as $pm)
                                                <tr class="border-bottom">
                                                    <td class="ps-4">
                                                        <div class="fw-medium">{{ $pm->device->name['en'] ?? 'Device' }}</div>
                                                        <div class="text-muted small">
                                                            {{ $pm->device->location ?? 'No location' }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="fw-medium">{{ $pm->next_pm_date }}</div>
                                                        <div class="text-muted small">
                                                            {{ \Carbon\Carbon::parse($pm->next_pm_date)->diffForHumans() }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge {{ $pm->status === 'done' ? 'bg-success bg-opacity-10 text-success' : 'bg-warning bg-opacity-10 text-warning' }}">
                                                            <i
                                                                class="fas fa-{{ $pm->status === 'done' ? 'check-circle' : 'clock' }} me-1"></i>
                                                            {{ strtoupper($pm->status) }}
                                                        </span>
                                                    </td>
                                                    <td class="pe-4 text-end">
                                                        <a href="{{ route('pm.plans.show', $pm->id) }}"
                                                            class="btn btn-sm btn-outline-primary px-3">
                                                            <i class="fas fa-eye me-1"></i> View
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center py-5">
                                                        <div class="py-4">
                                                            <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                                                            <h5 class="text-muted">No PM scheduled this week</h5>
                                                            <p class="text-muted small mb-0">Check next week's schedule</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- ===== CHART JS ===== --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // إصلاح مشكلة الفلاش بإخفاء المحتوى مؤقتاً
            const dashboardContainer = document.querySelector('.dashboard-container');
            if (dashboardContainer) {
                dashboardContainer.style.opacity = '0';
                dashboardContainer.style.transition = 'opacity 0.3s ease';

                setTimeout(() => {
                    dashboardContainer.style.opacity = '1';
                }, 100);
            }

            @if(isset($breakdownsChart) && $mode !== 'technician')
                const breakdownsCtx = document.getElementById('breakdownsChart')?.getContext('2d');
                if (breakdownsCtx) {
                    new Chart(breakdownsCtx, {
                        type: 'doughnut',
                        data: {
                            labels: {!! json_encode($breakdownsChart->keys()) !!},
                            datasets: [{
                                data: {!! json_encode($breakdownsChart->values()) !!},
                                backgroundColor: [
                                    '#dc3545',
                                    '#ffc107',
                                    '#17a2b8',
                                    '#28a745',
                                    '#6c757d'
                                ],
                                borderWidth: 2,
                                borderColor: '#fff'
                            }]
                        },
                        options: {
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        padding: 20,
                                        usePointStyle: true
                                    }
                                }
                            },
                            cutout: '70%',
                            maintainAspectRatio: false
                        }
                    });
                }
            @endif

                @if(isset($devicesChart) && $mode !== 'technician')
                    const devicesCtx = document.getElementById('devicesChart')?.getContext('2d');
                    if (devicesCtx) {
                        new Chart(devicesCtx, {
                            type: 'pie',
                            data: {
                                labels: {!! json_encode($devicesChart->keys()) !!},
                                datasets: [{
                                    data: {!! json_encode($devicesChart->values()) !!},
                                    backgroundColor: ['#17a2b8', '#6c757d', '#fd7e14', '#343a40'],
                                    borderWidth: 2,
                                    borderColor: '#fff'
                                }]
                            },
                            options: {
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            padding: 20,
                                            usePointStyle: true
                                        }
                                    }
                                },
                                cutout: '0%',
                                maintainAspectRatio: false
                            }
                        });
                    }
                @endif

                @if(isset($pmSoonCount) && $mode !== 'technician')
                    const pmCtx = document.getElementById('pmChart')?.getContext('2d');
                    if (pmCtx) {
                        new Chart(pmCtx, {
                            type: 'bar',
                            data: {
                                labels: ['Due Soon (30 days)', 'Later'],
                                datasets: [{
                                    data: [{{ $pmSoonCount }}, {{ $pmLaterCount }}],
                                    backgroundColor: [
                                        'rgba(0, 123, 255, 0.8)',
                                        'rgba(40, 167, 69, 0.8)'
                                    ],
                                    borderColor: [
                                        'rgba(0, 123, 255, 1)',
                                        'rgba(40, 167, 69, 1)'
                                    ],
                                    borderWidth: 1,
                                    borderRadius: 6
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            stepSize: 1
                                        }
                                    }
                                },
                                maintainAspectRatio: false
                            }
                        });
                    }
                @endif
            });
    </script>

    <style>
        /* إصلاحات خاصة بالداشبورد */

        /* إصلاح العرض على الجوال */
        @media (max-width: 767.98px) {
            .dashboard-container .table-responsive {
                margin-left: -12px;
                margin-right: -12px;
                width: calc(100% + 24px);
            }
            
            .dashboard-container .card {
                margin-bottom: 0.75rem;
            }
            
            .dashboard-container .row {
                margin-left: -0.5rem !important;
                margin-right: -0.5rem !important;
            }
            
            .dashboard-container [class*="col-"] {
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
                margin-bottom: 0.75rem;
            }
        }
        
        /* إصلاح العرض على التابلت */
        @media (min-width: 768px) and (max-width: 991.98px) {
            .dashboard-container .col-md-6 {
                flex: 0 0 50%;
                max-width: 50%;
            }
            
            .dashboard-container .col-lg-3 {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

        /* تحسين ظهور المحتوى */
        .dashboard-container {
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
        
        /* إصلاح الرسوم البيانية على الجوال */
        @media (max-width: 991.98px) {
            .chart-container {
                min-height: 200px;
            }
            
            #breakdownsChart,
            #devicesChart,
            #pmChart {
                max-height: 200px !important;
            }
        }
    </style>
@endsection