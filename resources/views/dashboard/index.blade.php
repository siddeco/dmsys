@extends('layouts.admin')

@section('content')

<div class="container p-4">

    <h2 class="mb-4">Dashboard Overview</h2>

    <div class="row">

        <!-- PM Records per Device -->
        <div class="col-md-6 mb-4">
            <canvas id="pmPerDeviceChart"></canvas>
        </div>

        <!-- PM Status Distribution -->
        <div class="col-md-6 mb-4">
            <canvas id="pmStatusChart"></canvas>
        </div>
    </div>

    <!-- Upcoming PM Table -->
    <h4 class="mt-5">Upcoming PM (Next 30 Days)</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Device</th>
                <th>Next PM Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($upcomingPm as $plan)
                <tr>
                    <td>{{ $plan->device->name['en'] ?? 'Unknown' }}</td>
                    <td>{{ $plan->next_pm_date }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2">No upcoming maintenance</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>


<!-- Load Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // 1) PM Records per Device
    const pmPerDeviceLabels = {!! json_encode($pmPerDevice->pluck('name')) !!};
    const pmPerDeviceCounts = {!! json_encode($pmPerDevice->pluck('pm_records_count')) !!};

    new Chart(document.getElementById('pmPerDeviceChart'), {
        type: 'bar',
        data: {
            labels: pmPerDeviceLabels,
            datasets: [{
                label: 'PM Records',
                data: pmPerDeviceCounts,
                backgroundColor: '#4e73df'
            }]
        }
    });

    // 2) PM Status Donut
    new Chart(document.getElementById('pmStatusChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($statusCounts->keys()) !!},
            datasets: [{
                data: {!! json_encode($statusCounts->values()) !!},
                backgroundColor: ['#1cc88a', '#f6c23e', '#e74a3b']
            }]
        }
    });
</script>

@endsection
