@extends('layouts.admin')

@section('content')

{{-- ================= TECHNICIAN DASHBOARD ================= --}}
@if($mode === 'technician')

<div class="container-fluid">

    <div class="row mb-4">

        {{-- My Breakdowns --}}
        <div class="col-md-6 mb-3">
            <a href="{{ route('breakdowns.index', ['assigned' => 'me']) }}"
               class="small-box bg-danger text-white">
                <div class="inner">
                    <h3>{{ $myBreakdowns }}</h3>
                    <p>My Breakdown Tasks</p>
                </div>
                <div class="icon"><i class="fas fa-bolt"></i></div>
            </a>
        </div>

        {{-- My PM --}}
        <div class="col-md-6 mb-3">
            <a href="{{ route('pm.plans.index', ['assigned' => 'me']) }}"
               class="small-box bg-primary text-white">
                <div class="inner">
                    <h3>{{ $myPmPlans }}</h3>
                    <p>My PM Tasks</p>
                </div>
                <div class="icon"><i class="fas fa-wrench"></i></div>
            </a>
        </div>

    </div>

    {{-- Latest --}}
    <div class="card">
        <div class="card-header bg-light">
            <strong>My Latest Breakdowns</strong>
        </div>

        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Device</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($latestBreakdowns as $b)
                    <tr>
                        <td>{{ $b->device->name['en'] ?? 'Device' }}</td>
                        <td>
                            <span class="badge bg-secondary">
                                {{ ucfirst($b->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('breakdowns.show', $b->id) }}"
                               class="btn btn-sm btn-outline-primary">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">
                            No assigned tasks
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ================= ADMIN / ENGINEER DASHBOARD ================= --}}
@else

<div class="container-fluid">

    {{-- ===== KPI CARDS ===== --}}
    <div class="row mb-4">

        <div class="col-lg-3 col-md-6 mb-3">
            <a href="{{ route('devices.index') }}"
               class="small-box bg-info text-white">
                <div class="inner">
                    <h3>{{ $totalDevices }}</h3>
                    <p>Total Devices</p>
                </div>
                <div class="icon"><i class="fas fa-tools"></i></div>
            </a>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <a href="{{ route('breakdowns.index', ['status' => 'open']) }}"
               class="small-box bg-danger text-white">
                <div class="inner">
                    <h3>{{ $openBreakdowns }}</h3>
                    <p>Open Breakdowns</p>
                </div>
                <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
            </a>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <a href="{{ route('breakdowns.index', ['status' => 'in_progress']) }}"
               class="small-box bg-warning text-dark">
                <div class="inner">
                    <h3>{{ $inProgressBreakdowns }}</h3>
                    <p>In Progress</p>
                </div>
                <div class="icon"><i class="fas fa-spinner"></i></div>
            </a>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <a href="{{ route('pm.plans.index', ['filter' => 'due_soon']) }}"
               class="small-box bg-primary text-white">
                <div class="inner">
                    <h3>{{ $pmDueSoon }}</h3>
                    <p>PM Due (30 Days)</p>
                </div>
                <div class="icon"><i class="fas fa-calendar-check"></i></div>
            </a>
        </div>

    </div>

    {{-- ===== SECONDARY ===== --}}
    <div class="row mb-4">

        <div class="col-lg-3 col-md-6 mb-3">
            <a href="{{ route('spare_parts.index', ['low_stock' => 1]) }}"
               class="small-box bg-secondary text-white">
                <div class="inner">
                    <h3>{{ $lowStockParts }}</h3>
                    <p>Low Stock Parts</p>
                </div>
                <div class="icon"><i class="fas fa-boxes"></i></div>
            </a>
        </div>

    </div>

    {{-- ===== ALERTS ===== --}}
    <div class="row mb-4">

        @if(!empty($criticalBreakdowns) && $criticalBreakdowns > 0)
        <div class="col-md-4">
            <div class="alert alert-danger">
                <strong>Critical SLA</strong><br>
                {{ $criticalBreakdowns }} breakdowns exceeded SLA
            </div>
        </div>
        @endif

       @if(isset($overduePm) && $overduePm > 0)
<div class="row mb-3">
    <div class="col-md-12">
        <div class="alert alert-warning d-flex align-items-center justify-content-between shadow-sm">
            <div>
                <i class="fas fa-calendar-times me-2"></i>
                <strong>PM Overdue!</strong>
                {{ $overduePm }} preventive maintenance tasks are overdue.
            </div>

            <a href="{{ route('pm.plans.index', ['overdue' => 1]) }}"
               class="btn btn-sm btn-outline-dark">
                View Overdue PM
            </a>
        </div>
    </div>
</div>
@endif



    </div>

    {{-- ===== CHARTS ===== --}}
    <div class="row">

        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-header">Breakdowns Status</div>
                <div class="card-body">
                    <canvas id="breakdownsChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-header">Devices Status</div>
                <div class="card-body">
                    <canvas id="devicesChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-header">PM Schedule</div>
                <div class="card-body">
                    <canvas id="pmChart"></canvas>
                </div>
            </div>
        </div>

    </div>


    {{-- ================= DASHBOARD TABLES ================= --}}
<div class="row mt-4">

    {{-- Latest Open Breakdowns --}}
    <div class="col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-header bg-danger text-white">
                <strong>Latest Open Breakdowns</strong>
            </div>

            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Device</th>
                            <th>Project</th>
                            <th>Location</th>
                            <th>Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestOpenBreakdowns as $b)
                            <tr>
                                <td>{{ $b->device->name}}</td>
                                <td>{{ $b->project->name ?? '-' }}</td>
                                <td>{{ $b->device->location ?? '-' }}</td>
                                <td>{{ $b->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('breakdowns.show', $b->id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">
                                    No open breakdowns
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- PM Plans This Week --}}
    <div class="col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <strong>PM Plans This Week</strong>
            </div>

            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Device</th>
                            <th>Next PM</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pmThisWeek as $pm)
                            <tr>
                                <td>{{ $pm->device->name['en'] ?? 'Device' }}</td>
                                <td>{{ $pm->next_pm_date }}</td>
                                <td>
                                    <span class="badge
                                        {{ $pm->status === 'done' ? 'bg-success' : 'bg-warning' }}">
                                        {{ strtoupper($pm->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('pm.plans.show', $pm->id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">
                                    No PM scheduled this week
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

{{-- ===== CHART JS ===== --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
@if(isset($breakdownsChart))
new Chart(document.getElementById('breakdownsChart'), {
    type: 'pie',
    data: {
        labels: {!! json_encode($breakdownsChart->keys()) !!},
        datasets: [{
            data: {!! json_encode($breakdownsChart->values()) !!},
            backgroundColor: [
                '#dc3545', // open - red
                '#ffc107', // assigned - yellow
                '#17a2b8', // in_progress - blue
                '#28a745', // resolved - green
                '#6c757d'  // closed - gray
            ]
        }]
    },
    options: {
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
@endif


@if(isset($devicesChart))
new Chart(document.getElementById('devicesChart'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($devicesChart->keys()) !!},
        datasets: [{
            data: {!! json_encode($devicesChart->values()) !!},
            backgroundColor: ['#17a2b8', '#6c757d', '#fd7e14', '#343a40']
        }]
    }
});
@endif

@if(isset($pmSoonCount))
new Chart(document.getElementById('pmChart'), {
    type: 'bar',
    data: {
        labels: ['Due Soon', 'Later'],
        datasets: [{
            data: [{{ $pmSoonCount }}, {{ $pmLaterCount }}],
            backgroundColor: ['#007bff', '#28a745']
        }]
    }
});
@endif
</script>

@endif
@endsection
