@extends('layouts.admin')

@section('title', 'Add PM Record')

@section('content')

<div class="container mt-4">

    {{-- Back Button --}}
    <a href="{{ route('pm.plans.show', $plan->id) }}" class="btn btn-secondary mb-3">
        ← Back to Plan
    </a>

    <div class="card">
        <div class="card-header bg-success text-white">
            <h4>Add Preventive Maintenance Record</h4>
        </div>

        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <b>There are validation errors:</b>
                    <ul class="mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    @ul>
                </div>
            @endif

            <form action="{{ route('pm.records.store', $plan->id) }}" method="POST">
                @csrf

                <div class="row">

                    {{-- Performed At --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Performed At (Date)</label>
                        <input type="date" name="performed_at" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Engineer</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled>
                   </div>

                    {{-- Status --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Device Status</label>
                        <select name="status" class="form-control" required>
                            <option value="ok">OK - Working Fine</option>
                            <option value="needs_parts">Needs Spare Parts</option>
                            <option value="critical">Critical Failure</option>
                        </select>
                    </div>

                    {{-- Report --}}
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Maintenance Report</label>
                        <textarea name="report" class="form-control" rows="4" placeholder="Write detailed PM report..."></textarea>
                    </div>

                </div>

                <div class="text-center mt-3">
                    <button class="btn btn-success btn-lg">Save PM Record</button>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection
