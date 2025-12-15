@extends('layouts.admin')

@section('content')

<div class="container-fluid">

{{-- ===============================
 🔍 Search & Filters
================================ --}}
<div class="card mb-3">
    <div class="card-header">
        <strong>
            <i class="fas fa-search me-1"></i>
            Search & Filters
        </strong>
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('devices.index') }}">
            <div class="row">

                {{-- Search text --}}
                <div class="col-md-4 mb-3">
                    <label class="form-label">Search</label>
                    <input type="text"
                           name="q"
                           class="form-control"
                           placeholder="Name, Model or Serial Number"
                           value="{{ request('q') }}">
                </div>

                {{-- Status --}}
                <div class="col-md-2 mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="">All</option>
                        <option value="active" {{ request('status')=='active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status')=='inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="under_maintenance" {{ request('status')=='under_maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                        <option value="out_of_service" {{ request('status')=='out_of_service' ? 'selected' : '' }}>Out of Service</option>
                    </select>
                </div>

                {{-- Project --}}
                <div class="col-md-3 mb-3">
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
                <div class="col-md-3 mb-3 d-flex align-items-end gap-2">
                    <button class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i> Apply
                    </button>

                    <a href="{{ route('devices.index') }}"
                       class="btn btn-outline-secondary w-100">
                        Reset
                    </a>
                </div>

            </div>
        </form>
    </div>
</div>


    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">{{ __('Devices List') }}</h3>

        @can('manage devices')
            <a href="{{ route('devices.create') }}" class="btn btn-primary">
                + Add Device
            </a>
        @endcan
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped table-bordered mb-0 align-middle">
                <thead>
                    <tr>
                        <th style="width:70px;">#</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Serial Number') }}</th>
                        <th>{{ __('Model') }}</th>
                        <th>{{ __('Project') }}</th>
                        <th>City</th>
                        <th style="width:160px;">{{ __('Status') }}</th>
                        <th style="width:140px;" class="text-center">{{ __('Actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($devices as $device)
                        <tr>
                            <td>{{ $device->id }}</td>

                            {{-- ✅ اسم الجهاز بشكل صحيح --}}
                            <td>{{ $device->name['en'] ?? $device->name ?? 'N/A' }}</td>

                            <td>{{ $device->serial_number }}</td>
                            <td>{{ $device->model }}</td>
                            <td>{{ $device->project->name ?? '-' }}</td>
                            <td>{{ $device->city }}</td>

                            <td>
                                <span class="badge bg-info">
                                    {{ $device->status }}
                                </span>
                            </td>

                            <td class="text-center">
    @can('manage devices')
        <div class="d-flex justify-content-center gap-2">

            {{-- Edit --}}
            <a href="{{ route('devices.edit', $device->id) }}"
               class="btn btn-sm btn-warning px-3">
                Edit
            </a>

            {{-- Archive --}}
            @if(!$device->is_archived)
                <form action="{{ route('devices.archive', $device->id) }}"
                      method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                            class="btn btn-sm btn-outline-danger px-3"
                            onclick="return confirm('Archive this device?')">
                        Archive
                    </button>
                </form>
            @endif

        </div>
    @endcan
</td>


                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-3">
                                {{ __('No devices found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $devices->withQueryString()->links() }}
        </div>
    </div>

</div>

@endsection
