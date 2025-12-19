@extends('layouts.admin')

@section('content')

    <div class="container mt-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Add New Spare Part</h3>
            <a href="{{ route('spare_parts.index') }}" class="btn btn-secondary">
                ← Back
            </a>
        </div>

        @can('manage spare parts')

            <!-- Form -->
            <div class="card shadow-sm">
                <div class="card-body">

                    <form action="{{ route('spare_parts.store') }}" method="POST">
                        @csrf

                        <!-- Name -->
                        <div class="mb-3">
                            <label class="form-label">Part Name</label>
                            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <!-- Part Number -->
                        <div class="mb-3">
                            <label class="form-label">Part Number</label>
                            <input type="text" name="part_number" class="form-control" value="{{ old('part_number') }}">
                        </div>

                        <!-- Manufacturer -->
                        <div class="mb-3">
                            <label class="form-label">Manufacturer</label>
                            <input type="text" name="manufacturer" class="form-control" value="{{ old('manufacturer') }}">
                        </div>

                        <!-- Device Optional -->
                        <div class="mb-3">
                            <label class="form-label">Device (Optional)</label>
                            <select name="device_id" class="form-control">
                                <option value="">-- Not linked to a specific device --</option>
                                @foreach ($devices as $device)
                                    <option value="{{ $device->id }}">
                                        {{ $device->name['en'] ?? $device->serial_number }}
                                        (SN: {{ $device->serial_number }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Quantity -->
                        <div class="mb-3">
                            <label class="form-label">Quantity in Stock</label>
                            <input type="number" name="quantity" class="form-control" value="{{ old('quantity', 0) }}" min="0"
                                required>
                        </div>

                        <!-- Alert Threshold -->
                        <div class="mb-3">
                            <label class="form-label">Alert Threshold</label>
                            <input type="number" name="alert_threshold" class="form-control"
                                value="{{ old('alert_threshold', 5) }}" min="0" required>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label class="form-label">Description (Optional)</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                        </div>

                        <!-- Submit -->
                        <button type="submit" class="btn btn-primary">
                            Save Spare Part
                        </button>

                    </form>

                </div>
            </div>

        @else

            <!-- No Permission -->
            <div class="alert alert-danger">
                You are not authorized to add spare parts.
            </div>

        @endcan

    </div>

@endsection