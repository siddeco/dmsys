@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Projects List</h3>

        @can('create projects')
        <a href="{{ route('projects.create') }}" class="btn btn-primary">+ Add Project</a>
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
