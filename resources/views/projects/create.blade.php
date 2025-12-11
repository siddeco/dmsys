@extends('layouts.admin')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Add New Project</h3>
        <a href="{{ route('projects.index') }}" class="btn btn-secondary btn-sm">Back to Projects</a>
    </div>

    @can('create projects')

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="{{ route('projects.store') }}" method="POST">
                @csrf

                <div class="row">

                    <!-- Project Name -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Project Name</label>
                        <input type="text" 
                               name="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               placeholder="Enter project name"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Client -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Client (Hospital / Company)</label>
                        <input type="text" 
                               name="client" 
                               class="form-control" 
                               placeholder="Client name">
                    </div>

                    <!-- City -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">City</label>
                        <input type="text" 
                               name="city" 
                               class="form-control" 
                               placeholder="Project city">
                    </div>

                    <!-- Start Date -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" 
                               name="start_date" 
                               class="form-control">
                    </div>

                    <!-- End Date -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">End Date</label>
                        <input type="date" 
                               name="end_date" 
                               class="form-control">
                    </div>

                    <!-- Description -->
                    <div class="col-12 mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" 
                                  class="form-control" 
                                  rows="4"
                                  placeholder="Notes about the project"></textarea>
                    </div>

                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">Save Project</button>

            </form>

        </div>
    </div>

    @else

    <div class="alert alert-danger">
        You are not authorized to create projects.
    </div>

    @endcan

</div>

@endsection
