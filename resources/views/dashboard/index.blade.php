@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    {{-- ================= CARDS ================= --}}
    <div class="row">

        <!-- Total Devices -->
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="{{ route('devices.index') }}" class="small-box bg-info text-white">
                <div class="inner">
                    <h3>{{ $totalDevices }}</h3>
                    <p>Total Devices</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tools"></i>
                </div>
            </a>
        </div>

        <!-- Open Breakdowns -->
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="{{ route('breakdowns.index', ['status' => 'open']) }}"
               class="small-box bg-danger text-white">
                <div class="inner">
                    <h3>{{ $openBreakdowns }}</h3>
                    <p>Open Work Orders</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </a>
        </div>

        <!-- In Progress -->
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="{{ route('breakdowns.index', ['status' => 'in_progress']) }}"
               class="small-box bg-warning text-dark">
                <div class="inner">
                    <h3>{{ $inProgressBreakdowns }}</h3>
                    <p>In Progress</p>
                </div>
                <div class="icon">
                    <i class="fas fa-spinner"></i>
                </div>
            </a>
        </div>

        <!-- PM Due Soon -->
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="{{ route('pm.plans.index', ['due' => 'soon']) }}"
               class="small-box bg-primary text-white">
                <div class="inner">
                    <h3>{{ $pmDueSoon }}</h3>
                    <p>PM Due in 30 Days</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </a>
        </div>

        <!-- Low Stock -->
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="{{ route('spare_parts.index', ['low_stock' => 1]) }}"
               class="small-box bg-secondary text-white">
                <div class="inner">
                    <h3>{{ $lowStockParts }}</h3>
                    <p>Low Stock Spare Parts</p>
                </div>
                <div class="icon">
                    <i class="fas fa-boxes"></i>
                </div>
            </a>
        </div>

    </div>

    {{-- ================= ALERTS ================= --}}
    <div class="row mt-3">

        @if(isset($criticalBreakdowns) && $criticalBreakdowns > 0)
            <div class="col-md-4 mb-2">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <strong>Critical!</strong>
                    {{ $criticalBreakdowns }} breakdowns exceeded SLA
                    <a href="{{ route('breakdowns.index', ['critical' => 1]) }}"
                       class="alert-link">Review</a>
                </div>
            </div>
        @endif

        @if(isset($overduePm) && $overduePm > 0)
            <div class="col-md-4 mb-2">
                <div class="alert alert-warning">
                    <i class="fas fa-calendar-times"></i>
                    <strong>PM Overdue!</strong>
                    {{ $overduePm }} PM plans are overdue
                    <a href="{{ route('pm.plans.index', ['overdue' => 1]) }}"
                       class="alert-link">View</a>
                </div>
            </div>
        @endif

       @if($outOfStockParts > 0)
<div class="col-md-4">
    <div class="alert alert-info d-flex align-items-center shadow-sm">
        <i class="fas fa-boxes fa-2x me-3 text-primary"></i>
        <div>
            <strong>Inventory Alert</strong><br>
            {{ $outOfStockParts }} spare parts reached minimum stock
            <br>
            <a href="{{ route('spare_parts.index', ['low_stock' => 1]) }}"
               class="alert-link">
                View Inventory
            </a>
        </div>
    </div>
</div>
@endif



    </div>

    {{-- ================= CHARTS ================= --}}
    <div class="row mt-4">

        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-header"><h6 class="mb-0">Breakdowns Status</h6></div>
                <div class="card-body">
                    <canvas id="breakdownsChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-header"><h6 class="mb-0">Devices Status</h6></div>
                <div class="card-body">
                    <canvas id="devicesChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-header"><h6 class="mb-0">PM Schedule</h6></div>
                <div class="card-body">
                    <canvas id="pmChart"></canvas>
                </div>
            </div>
        </div>

    </div>

    {{-- ================= LATEST BREAKDOWNS ================= --}}
    <div class="row mt-4">

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Latest Breakdown Requests</h6>
                </div>

                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Device</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($latestBreakdowns as $breakdown)
                            <tr>
                                <td>{{ $breakdown->device->name['en'] ?? 'Device' }}</td>
                                <td>
                                    <span class="badge
                                        {{ $breakdown->status == 'open' ? 'bg-danger' :
                                           ($breakdown->status == 'in_progress' ? 'bg-warning' : 'bg-success') }}">
                                        {{ ucfirst($breakdown->status) }}
                                    </span>
                                </td>
                                <td>{{ $breakdown->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('breakdowns.show', $breakdown->id) }}"
                                       class="btn btn-sm btn-outline-primary">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No breakdowns</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>

</div>

{{-- ================= CHART JS ================= --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
        labels: ['Due Soon (30 days)', 'Later'],
        datasets: [{
            label: 'PM Plans',
            data: [{{ $pmSoonCount }}, {{ $pmLaterCount }}],
            backgroundColor: ['#007bff', '#28a745']
        }]
    }
});
@endif
</script>

@endsection
