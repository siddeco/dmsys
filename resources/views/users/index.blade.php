@extends('layouts.app')

@section('title', 'Users Management')

@section('content')

    <div class="container-fluid">

        {{-- Header --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm bg-gradient-primary">
                    <div class="card-body py-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1 text-white">
                                <i class="fas fa-users me-2"></i>
                                Users Management
                            </h4>
                            <p class="mb-0 text-white-50">
                                Manage system users and roles
                            </p>
                        </div>

                        @can('manage users')
                            <a href="{{ route('users.create') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-plus me-1"></i> Add New User
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        {{-- Users Table --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="fas fa-list-alt text-primary me-2"></i>
                    All Users
                </h6>
                <span class="badge bg-primary">
                    {{ $users->total() }} Users
                </span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse($users as $user)
                            @php
                                $role = $user->roles->pluck('name')->first() ?? 'user';
                                $roleColors = [
                                    'admin' => 'danger',
                                    'engineer' => 'primary',
                                    'technician' => 'info',
                                    'user' => 'secondary',
                                ];
                            @endphp

                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3">
                                            <i class="fas fa-user text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $user->name }}</div>
                                            <div class="text-muted small">ID: {{ $user->id }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="text-muted">{{ $user->email }}</td>

                                <td>
                                    <span class="badge bg-{{ $roleColors[$role] ?? 'secondary' }}">
                                        {{ ucfirst($role) }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $user->is_active ? 'Active' : 'Disabled' }}
                                    </span>
                                </td>

                                <td class="text-end">
                                    @can('manage users')
                                        <div class="btn-group">
                                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="{{ route('users.toggle', $user) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-warning"
                                                    onclick="return confirm('Change user status?')">
                                                    <i class="fas fa-power-off"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-users fa-2x mb-2"></i><br>
                                    No users found
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="card-footer bg-white">
                {{ $users->links() }}
            </div>
        </div>

    </div>

    {{-- Styles --}}
    <style>
        .avatar-sm {
            width: 36px;
            height: 36px;
        }

        .btn-group .btn {
            border-radius: 6px !important;
        }
    </style>

@endsection