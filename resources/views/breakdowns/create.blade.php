@extends('layouts.app')

@section('content')

    <div class="container mt-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Create New Breakdown Ticket</h3>
            <a href="{{ route('breakdowns.index') }}" class="btn btn-secondary">
                ← Back
            </a>
        </div>

        <!-- Form -->
        <div class="card shadow-sm">
            <div class="card-body">

                <form action="{{ route('breakdowns.store') }}" method="POST">
                    @csrf

                    <!-- Device Selection -->
                    <div class="mb-3">
                        <label for="device_id" class="form-label">Device</label>
                        <select name="device_id" id="device_id" class="form-control" required>
                            <option value="">-- Select Device --</option>

                            @foreach ($devices as $device)
                                <option value="{{ $device->id }}">
                                    {{ $device->name['en'] ?? 'Device' }}
                                    (SN: {{ $device->serial_number }}) -
                                    Project: {{ $device->project->name ?? '---' }}
                                </option>
                            @endforeach

                        </select>

                        @error('device_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Issue Description -->
                    <div class="mb-3">
                        <label for="issue_description" class="form-label">Issue Description</label>
                        <textarea name="issue_description" id="issue_description" rows="4" class="form-control"
                            required></textarea>

                        @error('issue_description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary">
                        Create Breakdown
                    </button>

                </form>

            </div>
        </div>

    </div>

@endsection