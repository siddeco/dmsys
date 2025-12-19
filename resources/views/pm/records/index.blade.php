@extends('layouts.admin')

@section('title', 'PM Records')

@section('content')
    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="mb-0">
                    <i class="fas fa-clipboard-check me-1"></i>
                    PM Records
                </h3>
                <small class="text-muted">Completed preventive maintenance history</small>
            </div>
        </div>

        {{-- Filters --}}
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <form method="GET" action="{{ route('pm.records.index') }}">
                    <div class="row g-2 align-items-end">

                        <div class="col-md-4">
                            <label class="form-label">Search</label>
                            <input type="text" name="q" class="form-control" placeholder="Device name, SN, engineer"
                                value="{{ request('q') }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Result</label>
                            <select name="result" class="form-select">
                                <option value="">All</option>
                                @foreach(['ok', 'needs_parts', 'critical'] as $r)
                                    <option value="{{ $r }}" {{ request('result') === $r ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $r)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 d-flex gap-2">
                            <button class="btn btn-primary w-100">
                                <i class="fas fa-filter"></i>
                            </button>

                            <a href="{{ route('pm.records.index') }}" class="btn btn-outline-secondary w-100">
                                Reset
                            </a>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        {{-- Table --}}
        <div class="card shadow-sm">
            <div class="card-body p-0">

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Device</th>
                                <th>PM Plan</th>
                                <th>Result</th>
                                <th>Performed At</th>
                                <th>Engineer</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($records as $record)
                                <tr>
                                    <td class="text-muted">#{{ $record->id }}</td>

                                    <td>
                                        <div class="fw-semibold">
                                            {{ $record->device?->name ?? 'N/A' }}
                                        </div>
                                        <div class="text-muted small">
                                            SN: {{ $record->device?->serial_number ?? '-' }}
                                        </div>
                                    </td>

                                    <td>
                                        PM #{{ $record->pm_plan_id }}
                                    </td>

                                    <td>
                                        @php
                                            $map = [
                                                'ok' => 'success',
                                                'needs_parts' => 'warning',
                                                'critical' => 'danger',
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $map[$record->status] ?? 'secondary' }}">
                                            {{ ucfirst(str_replace('_', ' ', $record->status)) }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $record->performed_at?->format('Y-m-d H:i') }}
                                    </td>

                                    <td>
                                        {{ $record->engineer_name }}
                                    </td>

                                    <td class="text-center">
                                        <a href="{{ route('pm.records.show', $record) }}" class="btn btn-sm btn-outline-primary"
                                            title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        No PM records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="card-footer bg-white">
                {{ $records->links() }}
            </div>
        </div>

    </div>
@endsection