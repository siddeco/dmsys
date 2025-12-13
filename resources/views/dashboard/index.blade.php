@extends('layouts.admin')

@section('content')
@if($mode === 'technician')
<div class="row">

    <div class="col-md-6 mb-3">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $myBreakdowns }}</h3>
                <p>My Breakdown Tasks</p>
            </div>
            <div class="icon">
                <i class="fas fa-bolt"></i>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $myPmPlans }}</h3>
                <p>My PM Tasks</p>
            </div>
            <div class="icon">
                <i class="fas fa-wrench"></i>
            </div>
        </div>
    </div>

</div>

<div class="card mt-4">
    <div class="card-header">
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
                    <td>{{ ucfirst($b->status) }}</td>
                    <td>
                        <a href="{{ route('breakdowns.show', $b->id) }}"
                           class="btn btn-sm btn-outline-primary">
                            View
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">No tasks</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@else

<div class="container-fluid">

    {{-- ================= ADMIN CARDS ================= --}}
    @can('manage breakdowns')
    <div class="row">

        <div class="col-lg-3 col-md-6 mb-3">
            <a href="{{ route('devices.index') }}" class="small-box bg-info text-white">
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
                    <p>Open Work Orders</p>
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
            <a href="{{ route('pm.plans.index', ['due' => 'soon']) }}"
               class="small-box bg-primary text-white">
                <div class="inner">
                    <h3>{{ $pmDueSoon }}</h3>
                    <p>PM Due in 30 Days</p>
                </div>
                <div class="icon"><i class="fas fa-calendar-check"></i></div>
            </a>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <a href="{{ route('spare_parts.index', ['low_stock' => 1]) }}"
               class="small-box bg-secondary text-white">
                <div class="inner">
                    <h3>{{ $lowStockParts }}</h3>
                    <p>Low Stock Spare Parts</p>
                </div>
                <div class="icon"><i class="fas fa-boxes"></i></div>
            </a>
        </div>

    </div>
    @endcan

    {{-- ================= TECHNICIAN CARDS ================= --}}
    @can('work breakdowns')
    <div class="row">

        <div class="col-lg-4 col-md-6 mb-3">
            <div class="small-box bg-warning text-dark">
                <div class="inner">
                    <h3>{{ $myOpenBreakdowns ?? 0 }}</h3>
                    <p>My Active Breakdowns</p>
                </div>
                <div class="icon"><i class="fas fa-bolt"></i></div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-3">
            <div class="small-box bg-primary text-white">
                <div class="inner">
                    <h3>{{ $myPmDue ?? 0 }}</h3>
                    <p>My PM Due Soon</p>
                </div>
                <div class="icon"><i class="fas fa-wrench"></i></div>
            </div>
        </div>

    </div>
    @endcan

    {{-- ================= ADMIN ALERTS ================= --}}
    @can('manage breakdowns')
    <div class="row mt-3">

        @isset($criticalBreakdowns)
        @if($criticalBreakdowns > 0)
        <div class="col-md-4 mb-2">
            <div class="alert alert-danger">
                <strong>Critical!</strong>
                {{ $criticalBreakdowns }} breakdowns exceeded SLA
            </div>
        </div>
        @endif
        @endisset

        @isset($overduePm)
        @if($overduePm > 0)
        <div class="col-md-4 mb-2">
            <div class="alert alert-warning">
                <strong>PM Overdue!</strong>
                {{ $overduePm }} PM plans are overdue
            </div>
        </div>
        @endif
        @endisset

    </div>
    @endcan

    {{-- ================= ADMIN CHARTS ================= --}}
    @can('manage breakdowns')
    <div class="row mt-4">

        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header">Breakdowns Status</div>
                <div class="card-body">
                    <canvas id="breakdownsChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header">Devices Status</div>
                <div class="card-body">
                    <canvas id="devicesChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header">PM Schedule</div>
                <div class="card-body">
                    <canvas id="pmChart"></canvas>
                </div>
            </div>
        </div>

    </div>
    @endcan

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@can('manage breakdowns')
<script>
@if(isset($breakdownsChart))
new Chart(document.getElementById('breakdownsChart'), {
    type: 'pie',
    data: {
        labels: {!! json_encode($breakdownsChart->keys()) !!},
        datasets: [{
            data: {!! json_encode($breakdownsChart->values()) !!},
            backgroundColor: ['#dc3545', '#ffc107', '#28a745']
        }]
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
@endcan
@endif

@endsection
