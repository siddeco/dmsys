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
                                        <i class="fas fa-users me-2"></i>
                                        Users Management
                                    </h4>
                                    <p class="mb-0 text-white-50">
                                        Manage system users and their permissions
                                    </p>
                                </div>
                                <a href="{{ route('users.create') }}" class="btn btn-light btn-sm">
                                    <i class="fas fa-plus me-1"></i> Add New User
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="card border-0 shadow-sm mx-3">
                <div class="card-header bg-light py-3 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="fas fa-list-alt text-primary me-2"></i>
                            All Users
                        </h6>
                        <span class="badge bg-primary">
                            {{ $users->total() }} Users
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">User</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th class="pe-4 text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr class="border-bottom">
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3">
                                                    <i class="fas fa-user text-primary"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-medium">{{ $user->name }}</div>
                                                    <div class="text-muted small">
                                                        ID: {{ $user->id }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-muted">{{ $user->email }}</div>
                                        </td>
                                        <td>
                                            @php
                                                $roleColors = [
                                                    'admin' => 'danger',
                                                    'engineer' => 'primary',
                                                    'technician' => 'info',
                                                    'user' => 'secondary'
                                                ];
                                                $role = $user->roles->pluck('name')->first() ?? 'user';
                                            @endphp
                                            <span
                                                class="badge bg-{{ $roleColors[$role] ?? 'secondary' }} bg-opacity-10 text-{{ $roleColors[$role] ?? 'secondary' }}">
                                                {{ ucfirst($role) }}
                                            </span>
                                        </td>
                                        <td class="pe-4 text-end">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('users.edit', $user->id) }}"
                                                    class="btn btn-sm btn-outline-primary px-3" title="Edit User">
                                                    <i class="fas fa-edit me-1"></i> Edit
                                                </a>

                                                <button type="button" class="btn btn-sm btn-outline-danger px-3"
                                                    onclick="confirmDelete('{{ $user->id }}', '{{ $user->name }}')"
                                                    title="Delete User">
                                                    <i class="fas fa-trash me-1"></i> Delete
                                                </button>

                                                <form id="delete-form-{{ $user->id }}"
                                                    action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                    class="d-none">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                                @if($users->isEmpty())
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <div class="py-4">
                                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">No users found</h5>
                                                <p class="text-muted small mb-0">Start by adding your first user</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if($users->hasPages())
                    <div class="card-footer bg-transparent border-top-0 py-3">
                        <div class="d-flex justify-content-center">
                            {{ $users->links() }}
                        </div>
                    </div>
                @endif
            </div>
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
                        <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                    </div>
                    <h5 class="modal-title mb-2">Delete User</h5>
                    <p class="text-muted mb-4" id="deleteMessage">Are you sure you want to delete this user?</p>
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

        .avatar-sm {
            width: 36px;
            height: 36px;
        }

        .avatar-lg {
            width: 64px;
            height: 64px;
        }

        .btn-group .btn {
            border-radius: 6px !important;
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
        }
    </style>

    <script>
        function confirmDelete(userId, userName) {
            // Set the delete message
            document.getElementById('deleteMessage').textContent =
                `Are you sure you want to delete user "${userName}"? This action cannot be undone.`;

            // Set up the confirm button
            const confirmBtn = document.getElementById('confirmDeleteBtn');
            confirmBtn.onclick = function () {
                document.getElementById('delete-form-' + userId).submit();
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
        });
    </script>
@endsection