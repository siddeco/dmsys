@extends('layouts.app')

@section('content')
    <div class="dashboard-container">
        <div class="container-fluid px-0">
            <!-- Header Section -->
            <div class="row mb-4 mx-0">
                <div class="col-12 px-0">
                    <div class="card border-0 shadow-sm bg-gradient-primary">
                        <div class="card-body py-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="mb-1 text-white">
                                        <i class="fas fa-cogs me-2"></i>
                                        Spare Parts Inventory
                                    </h4>
                                    <p class="mb-0 text-white-50">
                                        Manage and track spare parts stock levels
                                    </p>
                                </div>
                                @can('manage spare parts')
                                    <a href="{{ route('spare_parts.create') }}" class="btn btn-light btn-sm">
                                        <i class="fas fa-plus me-1"></i> Add Spare Part
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @can('view spare parts')
                <!-- Quick Stats -->
                <div class="row mb-4 mx-0">
                    <div class="col-lg-3 col-md-6 mb-3 px-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="text-uppercase text-muted mb-2">Total Parts</h6>
                                        <h2 class="mb-0">{{ $parts->total() }}</h2>
                                        <p class="text-muted mb-0 mt-2">
                                            <span class="badge bg-info bg-opacity-10 text-info">
                                                <i class="fas fa-boxes me-1"></i>
                                                In inventory
                                            </span>
                                        </p>
                                    </div>
                                    <div
                                        class="avatar-sm bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="fas fa-cubes text-info"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3 px-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="text-uppercase text-muted mb-2">Low Stock</h6>
                                        <h2 class="mb-0 text-warning">{{ $lowStockCount ?? 0 }}</h2>
                                        <p class="text-muted mb-0 mt-2">
                                            <span class="badge bg-warning bg-opacity-10 text-warning">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                Needs attention
                                            </span>
                                        </p>
                                    </div>
                                    <div
                                        class="avatar-sm bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="fas fa-exclamation text-warning"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3 px-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="text-uppercase text-muted mb-2">Out of Stock</h6>
                                        <h2 class="mb-0 text-danger">{{ $outOfStockCount ?? 0 }}</h2>
                                        <p class="text-muted mb-0 mt-2">
                                            <span class="badge bg-danger bg-opacity-10 text-danger">
                                                <i class="fas fa-times-circle me-1"></i>
                                                Urgent restock
                                            </span>
                                        </p>
                                    </div>
                                    <div
                                        class="avatar-sm bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="fas fa-times text-danger"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3 px-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="text-uppercase text-muted mb-2">Unique Models</h6>
                                        <h2 class="mb-0 text-success">{{ $uniqueModelsCount ?? 0 }}</h2>
                                        <p class="text-muted mb-0 mt-2">
                                            <span class="badge bg-success bg-opacity-10 text-success">
                                                <i class="fas fa-server me-1"></i>
                                                Device types
                                            </span>
                                        </p>
                                    </div>
                                    <div
                                        class="avatar-sm bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="fas fa-microchip text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Success Alert -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4 mx-3" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Inventory Table -->
                <div class="card border-0 shadow-sm mx-3">
                    <div class="card-header bg-light py-3 border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="fas fa-list-alt text-primary me-2"></i>
                                All Spare Parts
                            </h6>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-primary me-2">
                                    {{ $parts->total() }} Items
                                </span>
                                @if(request('low_stock'))
                                    <a href="{{ route('spare_parts.index') }}" class="btn btn-sm btn-outline-secondary">
                                        Show All
                                    </a>
                                @else
                                    <a href="{{ route('spare_parts.index', ['low_stock' => 1]) }}"
                                        class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-exclamation-triangle me-1"></i> Low Stock Only
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Part Details</th>
                                        <th>Manufacturer</th>
                                        <th>Device</th>
                                        <th>Stock</th>
                                        <th>Status</th>
                                        <th class="pe-4 text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($parts as $part)
                                        <tr class="border-bottom {{ $part->isLow() ? 'table-warning' : '' }}">
                                            <td class="ps-4">
                                                <div class="fw-medium">{{ $part->name }}</div>
                                                <div class="text-muted small">
                                                    ID: {{ $part->id }}
                                                    @if($part->part_number)
                                                        | PN: {{ $part->part_number }}
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-muted">{{ $part->manufacturer ?? '—' }}</div>
                                                <div class="small text-muted">
                                                    @if($part->supplier)
                                                        Supplier: {{ $part->supplier }}
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if ($part->device)
                                                    <div class="fw-medium">{{ $part->device->name['en'] ?? 'Device' }}</div>
                                                    <div class="text-muted small">
                                                        SN: {{ $part->device->serial_number ?? '—' }}
                                                    </div>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                                        @php
                                                            $percentage = min(100, ($part->quantity / ($part->minimum_stock ?? 10)) * 100);
                                                            $color = $part->isLow() ? 'bg-warning' : ($part->quantity <= 0 ? 'bg-danger' : 'bg-success');
                                                        @endphp
                                                        <div class="progress-bar {{ $color }}" style="width: {{ $percentage }}%">
                                                        </div>
                                                    </div>
                                                    <div class="fw-medium">{{ $part->quantity }}</div>
                                                </div>
                                                @if($part->minimum_stock)
                                                    <div class="text-muted small">
                                                        Min: {{ $part->minimum_stock }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($part->isLow())
                                                    <span class="badge bg-warning bg-opacity-10 text-warning">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                                        Low Stock
                                                    </span>
                                                @elseif ($part->quantity <= 0)
                                                    <span class="badge bg-danger bg-opacity-10 text-danger">
                                                        <i class="fas fa-times-circle me-1"></i>
                                                        Out of Stock
                                                    </span>
                                                @else
                                                    <span class="badge bg-success bg-opacity-10 text-success">
                                                        <i class="fas fa-check-circle me-1"></i>
                                                        In Stock
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="pe-4 text-end">
                                                <div class="btn-group" role="group">
                                                    @can('manage spare parts')
                                                        <a href="{{ route('spare_parts.edit', $part->id) }}"
                                                            class="btn btn-sm btn-outline-primary px-3" title="Edit Part">
                                                            <i class="fas fa-edit me-1"></i> Edit
                                                        </a>
                                                    @endcan

                                                    @can('manage spare parts')
                                                        <button type="button" class="btn btn-sm btn-outline-danger px-3"
                                                            onclick="confirmDelete('{{ $part->id }}', '{{ $part->name }}')"
                                                            title="Delete Part">
                                                            <i class="fas fa-trash me-1"></i> Delete
                                                        </button>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <div class="py-4">
                                                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                                    <h5 class="text-muted">No spare parts found</h5>
                                                    <p class="text-muted small mb-0">
                                                        @can('manage spare parts')
                                                            Start by adding your first spare part
                                                        @else
                                                            No spare parts available in inventory
                                                        @endcan
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    @if($parts->hasPages())
                        <div class="card-footer bg-transparent border-top-0 py-3">
                            <div class="d-flex justify-content-center">
                                {{ $parts->links() }}
                            </div>
                        </div>
                    @endif
                </div>

            @else
                <!-- No Permission -->
                <div class="card border-0 shadow-sm mx-3">
                    <div class="card-body text-center py-5">
                        <div class="py-4">
                            <i class="fas fa-ban fa-3x text-danger mb-3"></i>
                            <h5 class="text-danger">Access Denied</h5>
                            <p class="text-muted mb-0">You are not authorized to view spare parts.</p>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center px-4 py-3">
                    <div
                        class="avatar-lg bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4">
                        <i class="fas fa-trash fa-2x text-danger"></i>
                    </div>
                    <h5 class="modal-title mb-2">Delete Spare Part</h5>
                    <p class="text-muted mb-4" id="deleteMessage">Are you sure you want to delete this spare part?</p>
                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-danger px-4" id="confirmDeleteBtn">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .dashboard-container .table-responsive {
            border-radius: 8px;
            overflow: hidden;
        }

        .progress {
            background-color: #e9ecef;
            border-radius: 3px;
            min-width: 60px;
        }

        .btn-group .btn {
            border-radius: 6px !important;
        }

        .table-warning {
            background-color: rgba(255, 193, 7, 0.05) !important;
        }

        @media (max-width: 768px) {
            .dashboard-container .table-responsive {
                margin-left: -12px;
                margin-right: -12px;
                width: calc(100% + 24px);
            }

            .btn-group {
                flex-direction: column;
                gap: 0.5rem;
            }

            .btn-group .btn {
                width: 100%;
            }

            .row.mx-0>[class*="col-"] {
                padding-left: 8px !important;
                padding-right: 8px !important;
            }
        }
    </style>

    <script>
        function confirmDelete(partId, partName) {
            // Set the delete message
            document.getElementById('deleteMessage').textContent =
                `Are you sure you want to delete "${partName}"? This action cannot be undone.`;

            // Set up the confirm button
            const confirmBtn = document.getElementById('confirmDeleteBtn');
            confirmBtn.onclick = function () {
                // Submit the delete form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `{{ url('spare_parts') }}/${partId}`;
                form.style.display = 'none';

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                document.body.appendChild(form);
                form.submit();
            };

            // Show the modal
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Initialize tooltips
            const tooltips = document.querySelectorAll('[title]');
            tooltips.forEach(tooltip => {
                new bootstrap.Tooltip(tooltip);
            });

            // Fix table responsiveness on mobile
            function fixTableResponsive() {
                const tables = document.querySelectorAll('.table-responsive');
                tables.forEach(table => {
                    if (window.innerWidth < 768) {
                        table.style.overflowX = 'auto';
                        table.style.WebkitOverflowScrolling = 'touch';
                    }
                });
            }

            fixTableResponsive();
            window.addEventListener('resize', fixTableResponsive);

            // Highlight low stock rows
            const lowStockRows = document.querySelectorAll('.table-warning');
            lowStockRows.forEach(row => {
                row.addEventListener('mouseenter', function () {
                    this.style.backgroundColor = 'rgba(255, 193, 7, 0.1)';
                });

                row.addEventListener('mouseleave', function () {
                    this.style.backgroundColor = 'rgba(255, 193, 7, 0.05)';
                });
            });
        });
    </script>
@endsection