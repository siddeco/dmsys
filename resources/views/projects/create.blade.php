@extends('layouts.admin')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Add New Project</h3>
        <a href="{{ route('projects.index') }}" class="btn btn-secondary btn-sm">
            Back to Projects
        </a>
    </div>

    @can('manage projects')

    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- ===============================
                   Project Information
                =============================== --}}
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Project Name</label>
                        <input type="text"
                               name="name"
                               class="form-control"
                               required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Client</label>
                        <input type="text"
                               name="client"
                               class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">City</label>
                        <input type="text"
                               name="city"
                               class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Start Date</label>
                        <input type="date"
                               name="start_date"
                               class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">End Date</label>
                        <input type="date"
                               name="end_date"
                               class="form-control">
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description"
                                  class="form-control"
                                  rows="3"></textarea>
                    </div>

                </div>

                {{-- ===============================
                   Project Documents
                =============================== --}}
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <strong>
                            <i class="fas fa-folder-open me-1"></i>
                            Project Documents
                        </strong>

                        <button type="button"
                                class="btn btn-sm btn-outline-primary"
                                onclick="addDocumentRow()">
                            + Add Document
                        </button>
                    </div>

                    <div class="card-body">

                        <div id="documents-wrapper">

                            {{-- Document Row --}}
                            <div class="row document-row mb-3">

                                <div class="col-md-4">
                                    <label class="form-label">Document Type</label>
                                    <select name="documents[type][]" class="form-control">
                                        <option value="contract">Contract</option>
                                        <option value="po">Purchase Order</option>
                                        <option value="warranty">Warranty</option>
                                        <option value="acceptance">Acceptance</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">File</label>
                                    <input type="file"
                                           name="documents[file][]"
                                           class="form-control"
                                           accept=".pdf,.jpg,.png,.doc,.docx">
                                </div>

                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button"
                                            class="btn btn-outline-danger w-100"
                                            onclick="removeDocumentRow(this)">
                                        Remove
                                    </button>
                                </div>

                            </div>

                        </div>

                        <small class="text-muted">
                            Allowed formats: PDF, JPG, PNG, DOC, DOCX
                        </small>

                    </div>
                </div>

                {{-- ===============================
                   Actions
                =============================== --}}
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Save Project
                    </button>

                    <a href="{{ route('projects.index') }}"
                       class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>

            </form>

        </div>
    </div>

    @else
        <div class="alert alert-danger">
            You are not authorized to create projects.
        </div>
    @endcan

</div>

{{-- ===============================
   JavaScript
=============================== --}}
<script>
    function addDocumentRow() {
        const wrapper = document.getElementById('documents-wrapper');

        const row = document.createElement('div');
        row.classList.add('row', 'document-row', 'mb-3');

        row.innerHTML = `
            <div class="col-md-4">
                <select name="documents[type][]" class="form-control">
                    <option value="contract">Contract</option>
                    <option value="po">Purchase Order</option>
                    <option value="warranty">Warranty</option>
                    <option value="acceptance">Acceptance</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="col-md-6">
                <input type="file"
                       name="documents[file][]"
                       class="form-control"
                       accept=".pdf,.jpg,.png,.doc,.docx">
            </div>

            <div class="col-md-2">
                <button type="button"
                        class="btn btn-outline-danger w-100"
                        onclick="removeDocumentRow(this)">
                    Remove
                </button>
            </div>
        `;

        wrapper.appendChild(row);
    }

    function removeDocumentRow(button) {
        button.closest('.document-row').remove();
    }
</script>

@endsection
