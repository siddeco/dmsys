@extends('layouts.admin')

@section('content')

    <div class="container-fluid">

        {{-- ================= HEADER ================= --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>
                <i class="fas fa-folder-open me-1"></i>
                Project Documents
            </h3>

            <a href="{{ route('projects.show', $project->id) }}" class="btn btn-outline-secondary">
                ← Back to Project
            </a>
        </div>

        {{-- ================= UPLOAD DOCUMENT ================= --}}
        @can('manage projects')
            <div class="card mb-4">
                <div class="card-header">
                    <strong>
                        <i class="fas fa-upload me-1"></i>
                        Upload New Document
                    </strong>
                </div>

                <div class="card-body">
                    <form action="{{ route('projects.documents.store', $project->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Document Type</label>
                                <select name="type" class="form-control" required>
                                    <option value="contract">Contract</option>
                                    <option value="po">Purchase Order</option>
                                    <option value="warranty">Warranty</option>
                                    <option value="acceptance">Acceptance</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">File</label>
                                <input type="file" name="file" class="form-control" accept=".pdf,.jpg,.png,.doc,.docx" required>
                            </div>

                            <div class="col-md-2 mb-3 d-flex align-items-end">
                                <button class="btn btn-primary w-100">
                                    <i class="fas fa-save"></i> Upload
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endcan

        {{-- ================= TABS ================= --}}
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <a class="nav-link {{ request('archived') ? '' : 'active' }}"
                    href="{{ route('projects.documents.index', $project->id) }}">
                    <i class="fas fa-folder me-1"></i>
                    Active Documents
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request('archived') ? 'active' : '' }}"
                    href="{{ route('projects.documents.index', [$project->id, 'archived' => 1]) }}">
                    <i class="fas fa-archive me-1"></i>
                    Archived Documents
                </a>
            </li>
        </ul>

        {{-- ================= SEARCH & FILTER ================= --}}
        <div class="card mb-3">
            <div class="card-header">
                <strong>
                    <i class="fas fa-search me-1"></i>
                    Search & Filter
                </strong>
            </div>

            <div class="card-body">
                <form method="GET" action="{{ route('projects.documents.index', $project->id) }}">
                    <input type="hidden" name="archived" value="{{ request('archived') }}">

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Search</label>
                            <input type="text" name="q" class="form-control" value="{{ request('q') }}"
                                placeholder="File name...">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-control">
                                <option value="">All</option>
                                @foreach(['contract', 'po', 'warranty', 'acceptance', 'other'] as $type)
                                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Uploaded By</label>
                            <select name="uploaded_by" class="form-control">
                                <option value="">All</option>
                                @foreach($uploaders as $user)
                                    <option value="{{ $user->id }}" {{ request('uploaded_by') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 mb-3 d-flex align-items-end gap-2">
                            <button class="btn btn-primary w-100">
                                <i class="fas fa-filter"></i>
                            </button>

                            <a href="{{ route('projects.documents.index', $project->id) }}"
                                class="btn btn-outline-secondary w-100">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- ================= DOCUMENTS TABLE ================= --}}
        <div class="card">
            <div class="card-header">
                <strong>
                    {{ request('archived') ? 'Archived Documents' : 'Documents List' }}
                </strong>
            </div>

            <div class="card-body p-0">
                <table class="table table-striped table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Type</th>
                            <th>File Name</th>
                            <th>Uploaded By</th>
                            <th>Date</th>
                            <th width="160" class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($documents as $doc)
                                            <tr>
                                                <td>{{ $doc->id }}</td>
                                                <td>{{ ucfirst($doc->type) }}</td>
                                                <td>{{ $doc->original_name }}</td>
                                                <td>{{ $doc->uploader->name ?? '-' }}</td>
                                                <td>{{ $doc->created_at->format('Y-m-d') }}</td>

                                                <td class="text-center">

                                                    {{-- Download --}}
                                                    <a href="{{ route('projects.documents.download', [
                                'project' => $project->id,
                                'document' => $doc->id
                            ]) }}" class="btn btn-sm btn-success me-1" title="Download">
                                                        <i class="fas fa-download"></i>
                                                    </a>


                                                    @can('manage projects')

                                                        @if(!request('archived'))
                                                                            {{-- Archive --}}
                                                                            <form action="{{ route('projects.documents.archive', [
                                                                'project' => $project->id,
                                                                'document' => $doc->id
                                                            ]) }}" method="POST" class="d-inline">
                                                                                @csrf
                                                                                @method('PATCH')
                                                                                <button class="btn btn-sm btn-outline-warning" title="Archive">
                                                                                    <i class="fas fa-archive"></i>
                                                                                </button>
                                                                            </form>

                                                        @else
                                                                            {{-- Restore --}}
                                                                            <form action="{{ route('projects.documents.restore', [
                                                                'project' => $project->id,
                                                                'document' => $doc->id
                                                            ]) }}" method="POST" class="d-inline">
                                                                                @csrf
                                                                                @method('PATCH')
                                                                                <button class="btn btn-sm btn-outline-success" title="Restore">
                                                                                    <i class="fas fa-undo"></i>
                                                                                </button>
                                                                            </form>

                                                        @endif

                                                    @endcan
                                                </td>
                                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-3">
                                    No documents found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                {{ $documents->withQueryString()->links() }}
            </div>
        </div>

    </div>

@endsection