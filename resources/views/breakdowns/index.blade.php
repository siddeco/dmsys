@extends('layouts.admin')

@section('content')

<div class="container mt-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Breakdown Tickets</h3>
        <a href="{{ route('breakdowns.create') }}" class="btn btn-primary">+ New Breakdown</a>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-bordered table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Device</th>
                        <th>Project</th>
                        <th>Issue</th>
                        <th>Status</th>
                        <th>Assigned To</th>
                        <th>Created At</th>
                        <th width="90">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($breakdowns as $bd)
                    <tr>
                        <td>{{ $bd->id }}</td>

                        <td>
                            {{ $bd->device->name }}
                            <br>
                            <small class="text-muted">SN: {{ $bd->device->serial_number }}</small>
                        </td>

                        <td>{{ $bd->project->name ?? '—' }}</td>

                        <td>{{ Str::limit($bd->issue_description, 40) }}</td>

                        <td>
                            @php
                                $colors = [
                                    'new' => 'secondary',
                                    'assigned' => 'info',
                                    'in_progress' => 'warning',
                                    'completed' => 'success',
                                ];
                            @endphp

                            <span class="badge bg-{{ $colors[$bd->status] ?? 'dark' }}">
                                {{ ucfirst(str_replace('_', ' ', $bd->status)) }}
                            </span>
                        </td>

                        <td>
                            {{ $bd->assignedEngineer->name ?? 'Not Assigned' }}
                        </td>

                        <td>{{ $bd->created_at->format('Y-m-d') }}</td>

                        <td>
                            <a href="{{ route('breakdowns.show', $bd->id) }}"
                               class="btn btn-sm btn-outline-primary">
                                View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            No breakdowns found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <!-- Pagination -->
        <div class="card-footer">
            {{ $breakdowns->links() }}
        </div>
    </div>

</div>

@endsection
