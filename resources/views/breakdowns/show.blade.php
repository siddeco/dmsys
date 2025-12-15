@extends('layouts.admin')

@section('content')

<div class="container mt-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Breakdown Details</h3>
        <a href="{{ route('breakdowns.index') }}" class="btn btn-secondary btn-sm">
            ← Back
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Breakdown Info --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">

                <div class="col-md-6 mb-2">
                    <strong>Device:</strong><br>
                    {{ $breakdown->device->name }}
                </div>

                <div class="col-md-6 mb-2">
                    <strong>Project:</strong><br>
                    {{ $breakdown->project->name  }}
                </div>

                
                <div class="col-md-6 mb-2">
                    <strong>Status:</strong><br>
                    <span class="badge
                        @switch($breakdown->status)
                            @case('open') bg-danger @break
                            @case('assigned') bg-secondary @break
                            @case('in_progress') bg-warning @break
                            @case('resolved') bg-info @break
                            @case('closed') bg-success @break
                        @endswitch">
                        {{ strtoupper($breakdown->status) }}
                    </span>
                </div>

                 <div class="col-md-6 mb-2">
                    <strong>Location:</strong><br>
                    {{ $breakdown->device->location  }}
                </div>

                <div class="col-md-6 mb-2">
                    <strong>Assigned To:</strong><br>
                    {{ $breakdown->assignedUser->name ?? 'Not Assigned' }}
                </div>

                <div class="col-md-12 mt-3">
                    <strong>Description:</strong>
                    <p class="mb-0">{{ $breakdown->description }}</p>
                </div>

                {{-- Service Report --}}
                @if($breakdown->engineer_report)
                    <div class="col-md-12 mt-3">
                        <strong>Service Report:</strong><br>
                        <a href="{{ asset('storage/'.$breakdown->engineer_report) }}"
                           target="_blank"
                           class="btn btn-outline-primary btn-sm mt-1">
                            📄 View Service Report
                        </a>
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- ================= WORKFLOW ACTIONS ================= --}}
    <div class="card">
        <div class="card-header">
            <strong>Workflow Actions</strong>
        </div>

        <div class="card-body">

            {{-- ASSIGN --}}
            @can('assign breakdowns')
                @if($breakdown->status === 'open')
                    <form method="POST" action="{{ route('breakdowns.assign', $breakdown) }}">
                        @csrf
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <select name="assigned_to" class="form-control" required>
                                    <option value="">-- Assign Technician --</option>
                                    @foreach($technicians as $tech)
                                        <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-primary">Assign</button>
                            </div>
                        </div>
                    </form>
                @endif
            @endcan

            {{-- START --}}
           @can('work breakdowns')
    @if(
        $breakdown->status === 'assigned' &&
        auth()->id() === $breakdown->assigned_to
    )
        <form method="POST"
              action="{{ route('breakdowns.start', $breakdown) }}"
              class="mt-3">
            @csrf
            <button class="btn btn-warning">
                Start Work
            </button>
        </form>
    @endif
@endcan


            {{-- RESOLVE (WITH REPORT REQUIRED) --}}
            @can('work breakdowns')
    @if(
        $breakdown->status === 'in_progress' &&
        auth()->id() === $breakdown->assigned_to
    )
        <form method="POST"
              action="{{ route('breakdowns.resolve', $breakdown) }}"
              enctype="multipart/form-data"
              class="mt-3">
            @csrf

            <div class="mb-3">
                <label><strong>Service Report (PDF / Image)</strong></label>
                <input type="file"
                       name="report_file"
                       class="form-control"
                       accept="image/*,application/pdf"
                       required>
            </div>

             {{-- Scan --}}
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
    </div>

   

            <textarea name="resolution_notes"
                      class="form-control mb-2"
                      placeholder="Optional notes..."></textarea>

            <button class="btn btn-success">
                Resolve Breakdown
            </button>
        </form>
    @endif
@endcan


            {{-- CLOSE --}}
            @can('manage breakdowns')
                @if($breakdown->status === 'resolved')
                    <form method="POST" action="{{ route('breakdowns.close', $breakdown) }}" class="mt-3">
                        @csrf
                        <button class="btn btn-danger">
                            Close Breakdown
                        </button>
                    </form>
                @endif
            @endcan

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
