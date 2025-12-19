@extends('layouts.admin')

@section('title', 'PM Record Details')

@section('content')
    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="mb-0">
                    <i class="fas fa-clipboard-check me-1"></i>
                    PM Record #{{ $record->id }}
                </h3>
                <small class="text-muted">
                    Completed on {{ $record->performed_at?->format('Y-m-d H:i') }}
                </small>
            </div>

            <a href="{{ route('pm.records.index') }}" class="btn btn-outline-secondary">
                ← Back to Records
            </a>
        </div>

        <div class="row g-3">

            {{-- LEFT COLUMN --}}
            <div class="col-lg-6">

                {{-- Device Info --}}
                <div class="card shadow-sm mb-3">
                    <div class="card-header">
                        <strong><i class="fas fa-microchip me-1"></i> Device</strong>
                    </div>
                    <div class="card-body">
                        <div><strong>Name:</strong> {{ $record->device?->name ?? 'N/A' }}</div>
                        <div><strong>Serial:</strong> {{ $record->device?->serial_number ?? '-' }}</div>
                        <div><strong>Project:</strong> {{ $record->device?->project?->name ?? '-' }}</div>
                    </div>
                </div>

                {{-- PM Plan --}}
                <div class="card shadow-sm mb-3">
                    <div class="card-header">
                        <strong><i class="fas fa-wrench me-1"></i> PM Plan</strong>
                    </div>
                    <div class="card-body">
                        <div><strong>Plan ID:</strong> #{{ $record->pm_plan_id }}</div>
                        <div><strong>Interval:</strong> {{ $record->pmPlan?->interval_months }} months</div>
                        <div><strong>Next PM:</strong>
                            {{ $record->pmPlan?->next_pm_date?->format('Y-m-d') ?? '-' }}
                        </div>
                    </div>
                </div>

            </div>

            {{-- RIGHT COLUMN --}}
            <div class="col-lg-6">

                {{-- Result --}}
                <div class="card shadow-sm mb-3">
                    <div class="card-header">
                        <strong><i class="fas fa-flag me-1"></i> Result</strong>
                    </div>
                    <div class="card-body">
                        @php
                            $map = [
                                'ok' => 'success',
                                'needs_parts' => 'warning',
                                'critical' => 'danger',
                            ];
                        @endphp

                        <span class="badge bg-{{ $map[$record->status] ?? 'secondary' }} fs-6">
                            {{ ucfirst(str_replace('_', ' ', $record->status)) }}
                        </span>

                        <div class="mt-3">
                            <strong>Engineer:</strong> {{ $record->engineer_name }}
                        </div>
                    </div>
                </div>

                {{-- Report --}}
                <div class="card shadow-sm mb-3">
                    <div class="card-header">
                        <strong><i class="fas fa-file-alt me-1"></i> Report</strong>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">
                            {{ $record->report ?? '—' }}
                        </p>
                    </div>
                </div>

            </div>

        </div>

        {{-- Attachments --}}
        <div class="card shadow-sm mt-3">
            <div class="card-header">
                <strong><i class="fas fa-paperclip me-1"></i> Attachments</strong>
            </div>

            <div class="card-body">

                <div class="d-flex flex-wrap gap-2">

                    {{-- Report File --}}
                    @if($record->report_file)
                        <a href="{{ asset('storage/' . $record->report_file) }}" target="_blank"
                            class="btn btn-outline-primary">
                            <i class="fas fa-file-pdf me-1"></i>
                            Service Report
                        </a>
                    @endif

                    {{-- Scan Image --}}
                    @if($record->scan_image)
                        <a href="{{ $record->scan_image }}" target="_blank" class="btn btn-outline-secondary">
                            <i class="fas fa-camera me-1"></i>
                            Scan Image
                        </a>
                    @endif

                    @if(!$record->report_file && !$record->scan_image)
                        <span class="text-muted">No attachments.</span>
                    @endif

                </div>

            </div>
        </div>

    </div>
@endsection