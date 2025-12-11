@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    <h3 class="mb-4">Edit Device</h3>

    <form action="{{ route('devices.update', $device->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">

            <div class="col-md-6 mb-3">
                <label>Name (English)</label>
                <input type="text" name="name_en" class="form-control"
                       value="{{ $device->name['en'] ?? '' }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Name (Arabic)</label>
                <input type="text" name="name_ar" class="form-control"
                       value="{{ $device->name['ar'] ?? '' }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Serial Number</label>
                <input type="text" name="serial_number" class="form-control"
                       value="{{ $device->serial_number }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Model</label>
                <input type="text" name="model" class="form-control"
                       value="{{ $device->model }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>Manufacturer</label>
                <input type="text" name="manufacturer" class="form-control"
                       value="{{ $device->manufacturer }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>Location</label>
                <input type="text" name="location" class="form-control"
                       value="{{ $device->location }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>Installation Date</label>
                <input type="date" name="installation_date" class="form-control"
                       value="{{ $device->installation_date }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="active" {{ $device->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $device->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="under_maintenance" {{ $device->status == 'under_maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                    <option value="out_of_service" {{ $device->status == 'out_of_service' ? 'selected' : '' }}>Out of Service</option>
                </select>
            </div>

            <div class="col-md-12 mb-3">
                <label>Project</label>
                <select name="project_id" class="form-control" required>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}"
                            {{ $project->id == $device->project_id ? 'selected' : '' }}>
                            {{ $project->name }} ({{ $project->client }})
                        </option>
                    @endforeach
                </select>
            </div>

        </div>

        <button class="btn btn-primary mt-3">Update Device</button>

    </form>

</div>

@endsection
