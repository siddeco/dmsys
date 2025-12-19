@extends('layouts.admin')

@section('title', 'Edit PM Plan')

@section('content')

    <div class="container mt-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>
                <i class="fas fa-edit me-1"></i>
                Edit PM Plan
            </h3>

            <a href="{{ route('pm.plans.show', $plan) }}" class="btn btn-outline-secondary">
                ← Back
            </a>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">

                <form method="POST" action="{{ route('pm.plans.update', $plan) }}">
                    @csrf
                    @method('PUT')

                    {{-- Device (Read Only) --}}
                    <div class="mb-3">
                        <label class="form-label">Device</label>
                        <input type="text" class="form-control"
                            value="{{ $plan->device->name ?? 'N/A' }} ({{ $plan->device->serial_number ?? '' }})" disabled>
                    </div>

                    {{-- Interval --}}
                    <div class="mb-3">
                        <label class="form-label">Interval (Months)</label>
                        <input type="number" name="interval_months" class="form-control"
                            value="{{ old('interval_months', $plan->interval_months) }}" min="1" required>
                    </div>

                    {{-- Next PM Date --}}
                    <div class="mb-3">
                        <label class="form-label">Next PM Date</label>
                        <input type="date" name="next_pm_date" class="form-control"
                            value="{{ old('next_pm_date', $plan->next_pm_date->format('Y-m-d')) }}" required>
                    </div>

                    {{-- Notes --}}
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $plan->notes) }}</textarea>
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('pm.plans.show', $plan) }}" class="btn btn-light">
                            Cancel
                        </a>

                        <button class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>
                            Save Changes
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>

@endsection