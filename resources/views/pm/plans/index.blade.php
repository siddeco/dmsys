@extends('layouts.admin')

@section('title', 'PM Plans')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Preventive Maintenance Plans</h3>
        <a href="{{ route('pm.plans.create') }}" class="btn btn-primary">+ Add PM Plan</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body p-0">

            <table class="table table-bordered table-striped mb-0">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Device</th>
                        <th>Interval (Months)</th>
                        <th>Next PM Date</th>
                        <th>Notes</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($plans as $plan)
                        <tr>
                            <td>{{ $plan->id }}</td>
                            <td>
                                {{ $plan->device->name ?? 'N/A' }}  
                                ({{ $plan->device->serial_number ?? '' }})
                            </td>
                            <td>{{ $plan->interval_months }}</td>
                            <td>{{ $plan->next_pm_date->format('Y-m-d') }}

    @if($plan->next_pm_date < now())
        <span class="badge bg-danger ms-2">Overdue</span>
    @elseif($plan->next_pm_date <= now()->addDays(30))
        <span class="badge bg-warning ms-2">Due Soon</span>
    @endif</td>
                            <td>{{ $plan->notes ?? '-' }}</td>

                            <td>
                                <a href="{{ route('pm.plans.show', $plan->id) }}" class="btn btn-sm btn-info">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No PM Plans found.</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>
    </div>

    <div class="mt-3">
        {{ $plans->links() }}
    </div>

</div>

@endsection
