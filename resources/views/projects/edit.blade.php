@extends('layouts.admin')

@section('content')
    <div class="container mt-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Edit Project</h3>
            <a href="{{ route('projects.show', $project->id) }}" class="btn btn-outline-secondary btn-sm">
                ← Back to Project
            </a>
        </div>

        @can('manage projects')

            <div class="card shadow-sm">
                <div class="card-body">

                    <form action="{{ route('projects.update', $project->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Project Name</label>
                                <input type="text" name="name" value="{{ old('name', $project->name) }}"
                                    class="form-control @error('name') is-invalid @enderror" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Client</label>
                                <input type="text" name="client" value="{{ old('client', $project->client) }}"
                                    class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">City</label>
                                <input type="text" name="city" value="{{ old('city', $project->city) }}" class="form-control">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="start_date" value="{{ old('start_date', $project->start_date) }}"
                                    class="form-control">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">End Date</label>
                                <input type="date" name="end_date" value="{{ old('end_date', $project->end_date) }}"
                                    class="form-control">
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control"
                                    rows="4">{{ old('description', $project->description) }}</textarea>
                            </div>

                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update
                            </button>

                            <a href="{{ route('projects.show', $project->id) }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>

                    </form>

                </div>
            </div>

        @else
            <div class="alert alert-danger">
                You are not authorized to edit projects.
            </div>
        @endcan

    </div>
@endsection