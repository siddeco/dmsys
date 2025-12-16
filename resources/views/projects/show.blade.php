@extends('layouts.admin')

@section('content')

    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>
                <i class="fas fa-project-diagram me-1"></i>
                Project Details
            </h3>

            <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary">
                ← Back to Projects
            </a>
        </div>

        {{-- Tabs --}}
        <ul class="nav nav-tabs mb-3" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ request('tab', 'info') === 'info' ? 'active' : '' }}"
                    href="{{ route('projects.show', $project->id) }}?tab=info">
                    Info
                </a>

            </li>

            <li class="nav-item">
                <a class="nav-link {{ request('tab') === 'devices' ? 'active' : '' }}"
                    href="{{ route('projects.show', $project->id) }}?tab=devices">
                    <i class="fas fa-tools me-1"></i> Devices
                </a>

            </li>

            <li class="nav-item">
                <a class="nav-link {{ request('tab') === 'documents' ? 'active' : '' }}"
                    href="{{ route('projects.show', $project->id) }}?tab=documents">
                    Documents
                </a>

            </li>
        </ul>

        <div class="tab-content">

            {{-- ================= INFO TAB ================= --}}
            <div class="tab-pane fade {{ request('tab', 'info') === 'info' ? 'show active' : '' }}">


                <div class="card mb-4">
                    <div class="card-header">
                        <strong>Project Information</strong>
                    </div>

                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-4 mb-2">
                                <strong>Name:</strong><br>
                                {{ $project->name }}
                            </div>

                            <div class="col-md-4 mb-2">
                                <strong>Client:</strong><br>
                                {{ $project->client ?? '-' }}
                            </div>

                            <div class="col-md-4 mb-2">
                                <strong>City:</strong><br>
                                {{ $project->city ?? '-' }}
                            </div>

                            <div class="col-md-4 mb-2">
                                <strong>Start Date:</strong><br>
                                {{ $project->start_date ?? '-' }}
                            </div>

                            <div class="col-md-4 mb-2">
                                <strong>Total Devices:</strong><br>
                                <span class="badge bg-primary">
                                    {{ $devicesCount }}
                                </span>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Devices Stats --}}
                <div class="row mb-4">
                    @foreach($devicesByStatus as $status => $count)
                        <div class="col-md-3 mb-2">
                            <div class="alert alert-info text-center">
                                <strong>{{ ucfirst(str_replace('_', ' ', $status)) }}</strong>
                                <div>{{ $count }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>

            {{-- ================= DEVICES TAB ================= --}}
            <div class="tab-pane fade {{ request('tab') === 'devices' ? 'show active' : '' }}">


                <div class="card" id="project-devices">
                    <div class="card-header">
                        <strong>Devices in this Project</strong>
                    </div>

                    <div class="card-body p-0">
                        <table class="table table-striped table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Serial</th>
                                    <th>Model</th>
                                    <th>City</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($devices as $device)
                                    <tr>
                                        <td>{{ $device->id }}</td>
                                        <td>{{ $device->name}}</td>
                                        <td>{{ $device->serial_number }}</td>
                                        <td>{{ $device->model ?? '-' }}</td>
                                        <td>{{ $device->city }}</td>
                                        <td>{{ $device->location ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ ucfirst(str_replace('_', ' ', $device->status)) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-3">
                                            No devices found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                    <div class="card-footer">
                        {{ $devices->links() }}
                    </div>
                </div>

            </div>

            {{-- ================= DOCUMENTS TAB ================= --}}
            <div class="tab-pane fade {{ request('tab') === 'documents' ? 'show active' : '' }}">


                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <strong>Project Documents</strong>

                        @can('manage projects')
                            <a href="{{ route('projects.documents.index', $project->id) }}"
                                class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-folder-open"></i>
                                Manage Documents
                            </a>

                        @endcan
                    </div>

                    <div class="card-body p-0">
                        <table class="table table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>File</th>
                                    <th>Uploaded At</th>
                                    <th width="120">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($project->documents as $doc)
                                    <tr>
                                        <td>{{ $doc->id }}</td>
                                        <td>{{ ucfirst($doc->type) }}</td>
                                        <td>{{ $doc->original_name }}</td>
                                        <td>{{ $doc->created_at->format('Y-m-d') }}</td>

                                        <td class="text-center">
                                            <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank"
                                                class="btn btn-success btn-sm" style="padding:6px 10px;"
                                                title="Download / View Document">
                                                <i class="fas fa-file-download"></i>

                                                <span>View</span>
                                            </a>
                                        </td>


                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-3">
                                            No documents uploaded.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const hash = window.location.hash;

                if (hash) {
                    const triggerEl = document.querySelector(
                        `button[data-bs-target="${hash}"]`
                    );

                    if (triggerEl) {
                        const tab = new bootstrap.Tab(triggerEl);
                        tab.show();
                    }
                }
            });
        </script>
    @endpush


@endsection