@extends('layouts.admin')

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Create Preventive Maintenance Plan</h3>
    </div>

    <div class="card-body">

        <form action="{{ route('pm.plans.store') }}" method="POST">
            @csrf

            <div class="row">

                <!-- Select Device -->
                <div class="col-md-6 mb-3">
                    <label for="device_id" class="form-label">Select Device</label>
                    <select name="device_id" class="form-control" required>
                        <option value="">-- Choose Device --</option>
                        @foreach($devices as $device)
                            <option value="{{ $device->id }}">
                                {{ $device->name }} ({{ $device->serial_number }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- PM Interval -->
                <div class="col-md-6 mb-3">
                    <label for="interval_months">PM Interval (Months)</label>
                    <input type="number" name="interval_months" value="6" min="1" class="form-control" required>
                </div>

                <!-- Next PM Date -->
                <div class="col-md-6 mb-3">
                    <label for="next_pm_date">Next PM Date</label>
                    <input type="date" name="next_pm_date" class="form-control" required>
                </div>

                <!-- Notes -->
                <div class="col-md-12 mb-3">
                    <label for="notes">Notes</label>
                    <textarea name="notes" class="form-control" rows="3"></textarea>
                </div>

            </div>

            <div class="text-center mt-4">
                <button class="btn btn-primary">Save PM Plan</button>
            </div>

        </form>

    </div>
</div>

@endsection
