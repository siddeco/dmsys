@extends('layouts.admin')

@section('title', 'PM Plan Details')

@section('content')

<div class="container mt-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>PM Plan Details</h3>
        <a href="{{ route('pm.records.create', $plan->id) }}" class="btn btn-success">
            + Add PM Record
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif


    {{-- Device Information --}}
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <b>Device Information</b>
        </div>

        <div class="card-body">
            <p><strong>Name:</strong> {{ $plan->device->name }}</p>
            <p><strong>Serial Number:</strong> {{ $plan->device->serial_number }}</p>
            <p><strong>Model:</strong> {{ $plan->device->model ?? '-' }}</p>
            <p><strong>Manufacturer:</strong> {{ $plan->device->manufacturer ?? '-' }}</p>
        </div>
    </div>

    {{-- Plan Information --}}
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <b>PM Plan Details</b>
        </div>

        <div class="card-body">
            <p><strong>Interval (Months):</strong> {{ $plan->interval_months }}</p>
            <p><strong>Next PM Date:</strong> {{ $plan->next_pm_formatted }}</p>
            <p><strong>Notes:</strong> {{ $plan->notes ?? '-' }}</p>
            <p><strong>Total PM Records:</strong> {{ $plan->records_count }}</p>
        </div>
    </div>

    {{-- PM Records Table --}}
    <div class="card">
        <div class="card-header bg-dark text-white">
            <b>PM Records History</b>
        </div>

        <div class="card-body p-0">
            <table class="table table-bordered table-striped mb-0">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Performed At</th>
                        <th>Engineer</th>
                        <th>Status</th>
                        <th>Report</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($plan->records as $record)
                        <tr>
                            <td>{{ $record->id }}</td>
                            <td>{{ $record->performed_at_formatted }}</td>
                            <td>{{ $record->engineer_name }}</td>
                            <td>
                                @if($record->status == 'ok')
                                    <span class="badge bg-success">OK</span>
                                @elseif($record->status == 'needs_parts')
                                    <span class="badge bg-warning">Needs Parts</span>
                                @elseif($record->status == 'critical')
                                    <span class="badge bg-danger">Critical</span>
                                @endif
                            </td>
                            <td>{{ $record->report ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No PM Records found.</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

</div>

@endsection
