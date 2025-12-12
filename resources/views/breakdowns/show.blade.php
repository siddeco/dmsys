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

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Breakdown Info --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">

                <div class="col-md-6 mb-2">
                    <strong>Device:</strong><br>
                    {{ $breakdown->device->name['en'] ?? 'N/A' }}
                </div>

                <div class="col-md-6 mb-2">
                    <strong>Project:</strong><br>
                    {{ $breakdown->project->name ?? 'N/A' }}
                </div>

                <div class="col-md-6 mb-2">
                    <strong>Status:</strong><br>
                    <span class="badge
                        {{ match($breakdown->status) {
                            'open' => 'bg-danger',
                            'assigned' => 'bg-secondary',
                            'in_progress' => 'bg-warning',
                            'resolved' => 'bg-info',
                            'closed' => 'bg-success',
                        } }}">
                        {{ strtoupper($breakdown->status) }}
                    </span>
                </div>

                <div class="col-md-6 mb-2">
                    <strong>Assigned To:</strong><br>
                    {{ $breakdown->assignedUser->name ?? 'Not Assigned' }}
                </div>

                <div class="col-md-12 mt-3">
                    <strong>Description:</strong>
                    <p class="mb-0">{{ $breakdown->description }}</p>
                </div>

            </div>
        </div>
    </div>

    {{-- ================= WORKFLOW ACTIONS ================= --}}
    <div class="card">
        <div class="card-header">
            <strong>Workflow Actions</strong>
        </div>

        <div class="card-body">

            {{-- ASSIGN (Admin / Engineer) --}}
            @can('manage breakdowns')
                @if($breakdown->status === 'open')
                    <form method="POST" action="{{ route('breakdowns.assign', $breakdown) }}">
                        @csrf
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <select name="assigned_to" class="form-control" required>
                                    <option value="">-- Assign Technician --</option>
                                    @foreach($technicians as $tech)
                                        <option value="{{ $tech->id }}">
                                            {{ $tech->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-primary">
                                    Assign
                                </button>
                            </div>
                        </div>
                    </form>
                @endif
            @endcan

            {{-- START WORK (Technician) --}}
            @can('work breakdowns')
                @if(
                    $breakdown->status === 'assigned' &&
                    auth()->id() === $breakdown->assigned_to
                )
                    <form method="POST" action="{{ route('breakdowns.start', $breakdown) }}" class="mt-3">
                        @csrf
                        <button class="btn btn-warning">
                            Start Work
                        </button>
                    </form>
                @endif
            @endcan

            {{-- RESOLVE --}}
            @can('work breakdowns')
                @if(
                    $breakdown->status === 'in_progress' &&
                    auth()->id() === $breakdown->assigned_to
                )
                    <form method="POST" action="{{ route('breakdowns.resolve', $breakdown) }}" class="mt-3">
                        @csrf
                        <div class="mb-2">
                            <textarea
                                name="resolution_notes"
                                class="form-control"
                                placeholder="Resolution notes..."
                                required></textarea>
                        </div>
                        <button class="btn btn-success">
                            Resolve Breakdown
                        </button>
                    </form>
                @endif
            @endcan

            {{-- CLOSE (Admin / Engineer) --}}
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

@endsection
