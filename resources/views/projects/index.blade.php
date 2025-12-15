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
        <form method="GET" action="{{ route('projects.index') }}">
            <div class="row">

                {{-- Search --}}
                <div class="col-md-4 mb-3">
                    <label class="form-label">Search</label>
                    <input type="text"
                           name="q"
                           class="form-control"
                           placeholder="Project name or client"
                           value="{{ request('q') }}">
                </div>

                {{-- Client --}}
                <div class="col-md-3 mb-3">
                    <label class="form-label">Client</label>
                    <select name="client" class="form-control">
                        <option value="">All</option>
                        <option value="MOH" {{ request('client')=='MOH' ? 'selected' : '' }}>MOH</option>
                        <option value="Private" {{ request('client')=='Private' ? 'selected' : '' }}>Private</option>
                    </select>
                </div>

                {{-- City --}}
                <div class="col-md-3 mb-3">
                    <label class="form-label">City</label>
                    <select name="city" class="form-control">
                        <option value="">All</option>
                        @foreach(['Riyadh','Jeddah','Makkah','Madinah','Tabuk','Asir','Hail','Najran','Al Jouf'] as $city)
                            <option value="{{ $city }}"
                                {{ request('city')==$city ? 'selected' : '' }}>
                                {{ $city }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Actions --}}
                <div class="col-md-2 mb-3 d-flex align-items-end gap-2">
                    <button class="btn btn-primary w-100">
                        Apply
                    </button>

                    <a href="{{ route('projects.index') }}"
                       class="btn btn-outline-secondary w-100">
                        Reset
                    </a>
                </div>

            </div>
        </form>
    </div>
</div>

  
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Projects List</h3>

        @can('manage projects')
<a href="{{ route('projects.create') }}" class="btn btn-primary">
    + Add Project
</a>
@endcan

    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Project Name</th>
                        <th>Client</th>
                        <th>City</th>
                        <th>Start Date</th>
                        <th>Devices</th>

                        @canany(['view projects', 'edit projects', 'delete projects'])
                        <th width="150">Actions</th>
                        @endcanany
                    </tr>
                </thead>

                <tbody>
                    @forelse($projects as $project)
                        <tr>
                            <td>{{ $project->id }}</td>
                            <td>{{ $project->name }}</td>
                            <td>{{ $project->client ?? '-' }}</td>
                            <td>{{ $project->city ?? '-' }}</td>
                            <td>{{ $project->start_date ?? '-' }}</td>

                            <td>
                                <span class="badge badge-info">
                                    {{ $project->devices->count() }} Devices
                                </span>
                            </td>

                            @canany(['view projects', 'edit projects', 'delete projects'])
                            <td>

                                @can('view projects')
                                <a href="{{ route('projects.show', $project->id) }}"
                                   class="btn btn-sm btn-success">View</a>
                                @endcan

                                @can('edit projects')
                                <a href="{{ route('projects.edit', $project->id) }}"
                                   class="btn btn-sm btn-warning">Edit</a>
                                @endcan

                                @can('delete projects')
                                <form action="{{ route('projects.destroy', $project->id) }}"
                                      method="POST" class="d-inline-block"
                                      onsubmit="return confirm('Are you sure you want to delete this project?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                                @endcan

                            </td>
                            @endcanany

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-3">
                                No projects found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $projects->links() }}
    </div>

</div>
@endsection
