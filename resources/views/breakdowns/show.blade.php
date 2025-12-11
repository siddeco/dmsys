@extends('layouts.admin')

@section('content')

<div class="container mt-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Breakdown Details</h3>
        <a href="{{ route('breakdowns.index') }}" class="btn btn-secondary">← Back</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <!-- Breakdown Info -->
            <h5 class="mb-3">Breakdown Information</h5>

            <table class="table table-bordered">
                <tr>
                    <th width="200">ID</th>
                    <td>{{ $breakdown->id }}</td>
                </tr>

                <tr>
                    <th>Device</th>
                    <td>
                        {{ $breakdown->device->name['en'] ?? 'N/A' }} <br>
                        <small class="text-muted">
                            SN: {{ $breakdown->device->serial_number }}
                        </small>
                    </td>
                </tr>

                <tr>
                    <th>Project</th>
                    <td>{{ $breakdown->project->name ?? '---' }}</td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>
                        @php
                            $colors = [
                                'new' => 'secondary',
                                'assigned' => 'info',
                                'in_progress' => 'warning',
                                'completed' => 'success',
                            ];
                        @endphp

                        <span class="badge bg-{{ $colors[$breakdown->status] ?? 'dark' }}">
                            {{ ucfirst(str_replace('_', ' ', $breakdown->status)) }}
                        </span>
                    </td>
                </tr>

                <tr>
                    <th>Issue Description</th>
                    <td>{{ $breakdown->issue_description }}</td>
                </tr>

                <tr>
                    <th>Assigned To</th>
                    <td>
                        {{ $breakdown->assignedEngineer->name ?? 'Not Assigned' }}
                    </td>
                </tr>

                <tr>
                    <th>Engineer Report</th>
                    <td>
                        {{ $breakdown->engineer_report ?? '—' }}
                    </td>
                </tr>

                <tr>
                    <th>Created At</th>
                    <td>{{ $breakdown->created_at->format('Y-m-d H:i') }}</td>
                </tr>

                <tr>
                    <th>Completed At</th>
                    <td>{{ $breakdown->completed_at ? $breakdown->completed_at->format('Y-m-d H:i') : '—' }}</td>
                </tr>
            </table>

        </div>
    </div>

</div>

@endsection
