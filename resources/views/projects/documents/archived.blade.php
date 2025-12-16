@extends('layouts.admin')

@section('content')

    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>
                <i class="fas fa-archive me-1"></i>
                Archived Project Documents
            </h3>

            <a href="{{ route('projects.documents.index', $project->id) }}" class="btn btn-outline-secondary">
                ← Back to Documents
            </a>
        </div>

        {{-- Info Alert --}}
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-1"></i>
            These documents are archived and not active. You can restore them anytime.
        </div>

        {{-- Archived Documents Table --}}
        <div class="card">
            <div class="card-body p-0">

                <table class="table table-striped table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Type</th>
                            <th>File Name</th>
                            <th>Uploaded By</th>
                            <th>Archived By</th>
                            <th>Archived At</th>
                            <th width="120" class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($documents as $doc)
                            <tr>
                                <td>{{ $doc->id }}</td>

                                <td>
                                    <span class="badge bg-secondary">
                                        {{ ucfirst($doc->type) }}
                                    </span>
                                </td>

                                <td>{{ $doc->original_name }}</td>

                                <td>{{ $doc->uploader->name ?? '-' }}</td>

                                <td>{{ $doc->archiver->name ?? '-' }}</td>

                                <td>
                                    {{ $doc->archived_at?->format('Y-m-d') ?? '-' }}
                                </td>

                                <td class="text-center">

                                    {{-- Download --}}
                                    <a href="{{ route('projects.documents.download', $doc->id) }}"
                                        class="btn btn-sm btn-outline-success me-1" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>

                                    {{-- Restore --}}
                                    @can('manage projects')
                                        <form action="{{ route('projects.documents.restore', $doc->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Restore this document?')">
                                            @csrf
                                            @method('PATCH')

                                            <button class="btn btn-sm btn-outline-primary" title="Restore">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        </form>
                                    @endcan

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-3">
                                    No archived documents found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>

            <div class="card-footer">
                {{ $documents->links() }}
            </div>
        </div>

    </div>

@endsection