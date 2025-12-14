@extends('layouts.admin')

@section('title', 'PM Plan Details')

@section('content')

<div class="container mt-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>PM Plan Details</h3>
        <a href="{{ route('pm.plans.index') }}" class="btn btn-secondary btn-sm">← Back</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- DEVICE INFO --}}
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <strong>Device Information</strong>
        </div>
        <div class="card-body">
            <p><strong>Name:</strong> {{ $plan->device->name['en'] ?? 'N/A' }}</p>
            <p><strong>Serial Number:</strong> {{ $plan->device->serial_number ?? 'N/A' }}</p>
            <p><strong>Model:</strong> {{ $plan->device->model ?? '-' }}</p>
            <p><strong>Manufacturer:</strong> {{ $plan->device->manufacturer ?? '-' }}</p>
        </div>
    </div>

    {{-- PLAN INFO --}}
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <strong>PM Plan Details</strong>
        </div>
        <div class="card-body">
            <p><strong>Interval (Months):</strong> {{ $plan->interval_months }}</p>
            <p><strong>Next PM Date:</strong> {{ $plan->next_pm_date }}</p>

            <p>
                <strong>Status:</strong>
                <span class="badge
                    @if($plan->status === 'pending') bg-secondary
                    @elseif($plan->status === 'assigned') bg-info
                    @elseif($plan->status === 'in_progress') bg-warning
                    @elseif($plan->status === 'done') bg-success
                    @endif">
                    {{ strtoupper($plan->status) }}
                </span>
            </p>

            <p>
                <strong>Assigned To:</strong>
                {{ $plan->assignedUser->name ?? 'Not Assigned' }}
            </p>

            <p><strong>Notes:</strong> {{ $plan->notes ?? '-' }}</p>
        </div>
    </div>

    {{-- WORKFLOW ACTIONS --}}
    <div class="card mb-4">
        <div class="card-header">
            <strong>Workflow Actions</strong>
        </div>

        <div class="card-body">

            {{-- ASSIGN PM (Admin / Engineer) --}}
            @can('manage pm')
                @if($plan->status === 'pending' && !$plan->assigned_to)
                    <form method="POST" action="{{ route('pm.plans.assign', $plan) }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <select name="assigned_to" class="form-control" required>
                                    <option value="">-- Assign Technician --</option>
                                    @foreach($technicians as $tech)
                                        <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-primary">Assign PM</button>
                            </div>
                        </div>
                    </form>
                @endif
            @endcan

            {{-- START PM (Technician only) --}}
            @can('work pm')
                @if(
                    $plan->status === 'assigned' &&
                    auth()->id() === $plan->assigned_to
                )
                    <form method="POST" action="{{ route('pm.plans.start', $plan) }}" class="mt-3">
                        @csrf
                        <button class="btn btn-warning">Start PM</button>
                    </form>
                @endif
            @endcan

            {{-- COMPLETE PM (Technician only) --}}
            @can('work pm')
               @if($plan->status === 'in_progress' && auth()->id() === $plan->assigned_to)

<form method="POST"
      action="{{ route('pm.plans.complete', $plan) }}"
      enctype="multipart/form-data"
      class="mt-3">
    @csrf

    {{-- Result --}}
    <div class="mb-3">
        <label><strong>PM Result</strong></label>
        <select name="status" class="form-control" required>
            <option value="">-- Select Result --</option>
            <option value="ok">OK</option>
            <option value="needs_parts">Needs Parts</option>
            <option value="critical">Critical</option>
        </select>
    </div>

    {{-- UPLOAD OPTION --}}
<div class="mb-3">
    <label><strong>Service Report (Upload PDF / Image)</strong></label>
    <input type="file"
           name="report_file"
           class="form-control"
           accept="image/*,application/pdf">
</div>

    {{-- SCAN TOOL --}}
<div class="mb-3">
    <label><strong>Service Report (Scan)</strong></label>

    <video id="video" width="100%" autoplay class="border rounded"></video>

    <canvas id="canvas" class="d-none"></canvas>

    <input type="hidden" name="scan_image" id="scan_image">

    <div class="mt-2 d-flex gap-2">
        <button type="button" class="btn btn-outline-primary" onclick="startCamera()">
            📷 Start Camera
        </button>

        <button type="button" class="btn btn-outline-success" onclick="takeSnapshot()">
            📸 Capture
        </button>
    </div>

    <small class="text-muted">
        Capture scanned service report before completing PM
    </small>
</div>


    <button class="btn btn-success">
        Complete PM
    </button>
</form>

@endif

            @endcan

        </div>
    </div>

    {{-- PM RECORDS --}}
    <div class="card">
        <div class="card-header bg-dark text-white">
            <strong>PM Records History</strong>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Engineer</th>
                        <th>Status</th>
                        <th>Report</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($plan->records as $record)
                    <tr>
                        <td>{{ $record->id }}</td>
                        <td>{{ $record->performed_at }}</td>
                        <td>{{ $record->engineer_name }}</td>
                        <td>
                            <span class="badge
                                @if($record->status === 'ok') bg-success
                                @elseif($record->status === 'needs_parts') bg-warning
                                @elseif($record->status === 'critical') bg-danger
                                @endif">
                                {{ strtoupper($record->status) }}
                            </span>
                        </td>
                        <td>{{ $record->report }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No PM records yet.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
let videoStream = null;

function startCamera() {
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
            videoStream = stream;
            const video = document.getElementById('video');
            video.srcObject = stream;
            video.play();
        })
        .catch(error => {
            alert('Camera access denied or not supported');
            console.error(error);
        });
}

function takeSnapshot() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    const context = canvas.getContext('2d');
    context.drawImage(video, 0, 0);

    const imageData = canvas.toDataURL('image/png');
    document.getElementById('scan_image').value = imageData;

    alert('Image captured successfully ✔');
}
</script>



@endsection
