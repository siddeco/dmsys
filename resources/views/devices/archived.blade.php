@extends('layouts.admin')

@section('content')

<div class="container-fluid">


    {{-- ===============================
 🔍 Search Archived Devices
================================ --}}
<div class="card mb-3">
    <div class="card-header">
        <strong>
            <i class="fas fa-search me-1"></i>
            Search Archived Devices
        </strong>
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('devices.archived') }}">
            <div class="row">

                {{-- Search --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Search</label>
                    <input type="text"
                           name="q"
                           class="form-control"
                           placeholder="Name, Model or Serial Number"
                           value="{{ request('q') }}">
                </div>

                {{-- Project --}}
                <div class="col-md-4 mb-3">
                    <label class="form-label">Project</label>
                    <select name="project_id" class="form-control">
                        <option value="">All Projects</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}"
                                {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Actions --}}
                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i> Apply
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>


    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>
            <i class="fas fa-archive me-1"></i>
            Archived Devices
        </h3>

        <a href="{{ route('devices.index') }}"
           class="btn btn-outline-secondary">
            ← Back to Devices
        </a>
    </div>

    {{-- Info Alert --}}
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i>
        These devices are archived and not active in maintenance or breakdown workflows.
    </div>

    {{-- Archived Devices Table --}}
    <div class="card">
        <div class="card-body p-0">

            <table class="table table-striped table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Serial Number</th>
                        <th>Model</th>
                        <th>Project</th>
                        <th>City</th>
                        <th>Status</th>
                        <th width="120" class="text-center">Actions</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($devices as $device)
                    <tr>
                        <td>{{ $device->id }}</td>
                        <td>{{ $device->name }}</td>
                        <td>{{ $device->serial_number }}</td>
                        <td>{{ $device->model ?? '-' }}</td>
                        <td>{{ $device->project->name ?? '-' }}</td>
                        <td>{{ $device->city }}</td>

                        <td>
                            <span class="badge bg-secondary">
                                Archived
                            </span>
                        </td>

                        <td class="text-center">

                            {{-- Restore --}}
                            @can('manage devices')
                            <form action="{{ route('devices.restore', $device->id) }}"
                                  method="POST"
                                  class="d-inline">
                                @csrf
                                @method('PATCH')

                                <button class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1"
        title="Restore Device"
        onclick="return confirm('Restore this device?')">
    <i class="fas fa-undo"></i>
    <span class="d-none d-md-inline">Restore</span>
</button>

                            </form>
                            @endcan

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-3">
                            No archived devices found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

        </div>

        <div class="card-footer">
            {{ $devices->links() }}
        </div>
    </div>

</div>

@endsection
