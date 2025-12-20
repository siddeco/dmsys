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
                                        <i class="fas fa-user-edit me-2"></i>
                                        Edit User
                                    </h4>
                                    <p class="mb-0 text-white-50">
                                        Update user information and permissions
                                    </p>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('users.index') }}" class="btn btn-light btn-sm">
                                        <i class="fas fa-arrow-left me-1"></i> Back to Users
                                    </a>
                                    <span class="badge bg-white text-dark">
                                        ID: {{ $user->id }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit User Form -->
            <div class="row justify-content-center mx-0">
                <div class="col-lg-8 col-md-10 px-3">
                    <!-- User Info Card -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4">
                                <div
                                    class="avatar-lg bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-4">
                                    <i class="fas fa-user text-primary fa-2x"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1">{{ $user->name }}</h5>
                                    <p class="text-muted mb-0">{{ $user->email }}</p>
                                    @php
                                        $role = $user->roles->pluck('name')->first() ?? 'user';
                                        $roleColors = [
                                            'admin' => 'danger',
                                            'engineer' => 'primary',
                                            'technician' => 'info',
                                            'user' => 'secondary'
                                        ];
                                        $color = $roleColors[$role] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} mt-2">
                                        <i class="fas fa-shield-alt me-1"></i>
                                        {{ ucfirst($role) }}
                                    </span>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-calendar-alt text-muted me-2"></i>
                                        <div>
                                            <small class="text-muted">Created</small>
                                            <div>{{ $user->created_at->format('Y-m-d') }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-clock text-muted me-2"></i>
                                        <div>
                                            <small class="text-muted">Last Updated</small>
                                            <div>{{ $user->updated_at->format('Y-m-d') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Form -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light py-3 border-0">
                            <h6 class="mb-0">
                                <i class="fas fa-edit text-primary me-2"></i>
                                Update Information
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('users.update', $user->id) }}" method="POST" id="editUserForm">
                                @csrf @method('PUT')

                                <!-- Name Field -->
                                <div class="mb-4">
                                    <label class="form-label fw-medium mb-2">
                                        <i class="fas fa-user me-1 text-primary"></i>
                                        Full Name
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-user text-muted"></i>
                                        </span>
                                        <input type="text" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email Field -->
                                <div class="mb-4">
                                    <label class="form-label fw-medium mb-2">
                                        <i class="fas fa-envelope me-1 text-primary"></i>
                                        Email Address
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-at text-muted"></i>
                                        </span>
                                        <input type="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-text">This will be used for login and notifications</div>
                                </div>

                                <!-- Password Field -->
                                <div class="mb-4">
                                    <label class="form-label fw-medium mb-2">
                                        <i class="fas fa-lock me-1 text-primary"></i>
                                        New Password
                                        <span class="badge bg-info bg-opacity-10 text-info ms-2">
                                            Optional
                                        </span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-key text-muted"></i>
                                        </span>
                                        <input type="password" name="password" id="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="Leave empty to keep current password">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @error('password')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-text">Minimum 8 characters with letters and numbers</div>
                                    <div class="password-strength mt-2">
                                        <div class="progress" style="height: 4px;">
                                            <div class="progress-bar" id="passwordStrength" style="width: 0%;"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Role Field -->
                                <div class="mb-4">
                                    <label class="form-label fw-medium mb-2">
                                        <i class="fas fa-user-tag me-1 text-primary"></i>
                                        User Role
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-shield-alt text-muted"></i>
                                        </span>
                                        <select name="role" class="form-select @error('role') is-invalid @enderror"
                                            required>
                                            @foreach($roles as $role)
                                                @php
                                                    $roleColors = [
                                                        'admin' => 'danger',
                                                        'engineer' => 'primary',
                                                        'technician' => 'info',
                                                        'user' => 'secondary'
                                                    ];
                                                    $color = $roleColors[$role->name] ?? 'secondary';
                                                    $isSelected = old('role', $user->roles->pluck('name')->first()) == $role->name;
                                                @endphp
                                                <option value="{{ $role->name }}" {{ $isSelected ? 'selected' : '' }}
                                                    data-color="{{ $color }}">
                                                    {{ ucfirst($role->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('role')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Role Descriptions -->
                                    <div class="mt-3">
                                        <div class="row g-2" id="roleDescriptions">
                                            <div class="col-12">
                                                <div class="alert alert-danger border-0 py-2 px-3 d-none" id="adminDesc">
                                                    <small><i class="fas fa-info-circle me-1"></i> Admin has full system
                                                        access and can manage all users and settings.</small>
                                                </div>
                                                <div class="alert alert-primary border-0 py-2 px-3 d-none"
                                                    id="engineerDesc">
                                                    <small><i class="fas fa-info-circle me-1"></i> Engineer can manage
                                                        devices, breakdowns, and PM plans.</small>
                                                </div>
                                                <div class="alert alert-info border-0 py-2 px-3 d-none" id="technicianDesc">
                                                    <small><i class="fas fa-info-circle me-1"></i> Technician can view
                                                        assigned tasks and update breakdown status.</small>
                                                </div>
                                                <div class="alert alert-secondary border-0 py-2 px-3 d-none" id="userDesc">
                                                    <small><i class="fas fa-info-circle me-1"></i> Basic user with limited
                                                        viewing permissions.</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Form Actions -->
                                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                                    <div>
                                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary px-4">
                                            <i class="fas fa-times me-1"></i> Cancel
                                        </a>
                                        <button type="button" class="btn btn-outline-danger px-4 ms-2"
                                            onclick="confirmReset()">
                                            <i class="fas fa-redo me-1"></i> Reset
                                        </button>
                                    </div>
                                    <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                                        <i class="fas fa-save me-1"></i> Update User
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Security Notice -->
                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start">
                                <div class="me-3">
                                    <i class="fas fa-shield-alt fa-2x text-warning"></i>
                                </div>
                                <div>
                                    <h6 class="mb-2">Security Notes</h6>
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-2">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <small>Email changes will affect login credentials</small>
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <small>Role changes affect system permissions immediately</small>
                                        </li>
                                        <li>
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <small>User will be notified of significant changes</small>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reset Confirmation Modal -->
    <div class="modal fade" id="resetModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center px-4 py-3">
                    <div
                        class="avatar-lg bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4">
                        <i class="fas fa-redo fa-2x text-warning"></i>
                    </div>
                    <h5 class="modal-title mb-2">Reset Form</h5>
                    <p class="text-muted mb-4">Are you sure you want to reset all changes? This will restore original
                        values.</p>
                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-warning px-4" id="confirmResetBtn">
                            Reset Form
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .dashboard-container .card {
            border-radius: 12px;
        }

        .avatar-lg {
            width: 80px;
            height: 80px;
        }

        .input-group-text {
            border-right: none;
        }

        .form-control,
        .form-select {
            border-left: none;
            padding-left: 0;
        }

        .password-strength .progress {
            background-color: #e9ecef;
            border-radius: 2px;
        }

        @media (max-width: 768px) {
            .dashboard-container .container-fluid {
                padding-left: 12px !important;
                padding-right: 12px !important;
            }

            .card-body {
                padding: 1.5rem !important;
            }

            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 1rem;
            }

            .d-flex.justify-content-between>div {
                width: 100%;
            }

            .d-flex.justify-content-between .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Store original form values
            const originalValues = {
                name: document.querySelector('input[name="name"]').value,
                email: document.querySelector('input[name="email"]').value,
                role: document.querySelector('select[name="role"]').value
            };

            // Toggle password visibility
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');

            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function () {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
                });
            }

            // Password strength indicator
            if (passwordInput) {
                passwordInput.addEventListener('input', function () {
                    const password = this.value;
                    if (!password) {
                        document.getElementById('passwordStrength').style.width = '0%';
                        return;
                    }

                    let strength = 0;

                    // Length check
                    if (password.length >= 8) strength += 25;
                    if (password.length >= 12) strength += 25;

                    // Complexity checks
                    if (/[A-Z]/.test(password)) strength += 25;
                    if (/[0-9]/.test(password)) strength += 25;
                    if (/[^A-Za-z0-9]/.test(password)) strength += 25;

                    // Cap at 100%
                    strength = Math.min(strength, 100);

                    const strengthBar = document.getElementById('passwordStrength');
                    if (strengthBar) {
                        strengthBar.style.width = strength + '%';

                        // Color based on strength
                        if (strength < 50) {
                            strengthBar.className = 'progress-bar bg-danger';
                        } else if (strength < 75) {
                            strengthBar.className = 'progress-bar bg-warning';
                        } else {
                            strengthBar.className = 'progress-bar bg-success';
                        }
                    }
                });
            }

            // Role description display
            const roleSelect = document.querySelector('select[name="role"]');
            if (roleSelect) {
                roleSelect.addEventListener('change', function () {
                    // Hide all descriptions
                    document.querySelectorAll('#roleDescriptions .alert').forEach(alert => {
                        alert.classList.add('d-none');
                    });

                    // Show selected role description
                    const selectedRole = this.value;
                    const descElement = document.getElementById(selectedRole + 'Desc');
                    if (descElement) {
                        descElement.classList.remove('d-none');

                        // Add animation
                        descElement.style.opacity = '0';
                        descElement.style.transition = 'opacity 0.3s';
                        setTimeout(() => {
                            descElement.style.opacity = '1';
                        }, 10);
                    }
                });

                // Trigger change on load
                roleSelect.dispatchEvent(new Event('change'));
            }

            // Form validation and submission
            const form = document.getElementById('editUserForm');
            const submitBtn = document.getElementById('submitBtn');

            if (form && submitBtn) {
                form.addEventListener('submit', function (e) {
                    // Check if form has changes
                    const currentName = document.querySelector('input[name="name"]').value;
                    const currentEmail = document.querySelector('input[name="email"]').value;
                    const currentRole = document.querySelector('select[name="role"]').value;
                    const password = document.querySelector('input[name="password"]').value;

                    const hasChanges = currentName !== originalValues.name ||
                        currentEmail !== originalValues.email ||
                        currentRole !== originalValues.role ||
                        password.length > 0;

                    if (!hasChanges) {
                        e.preventDefault();
                        alert('No changes were made to update.');
                        return false;
                    }

                    // Disable submit button to prevent double submission
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Updating...';
                    submitBtn.classList.add('disabled');

                    return true;
                });
            }

            // Reset form function
            window.confirmReset = function () {
                const resetModal = new bootstrap.Modal(document.getElementById('resetModal'));
                resetModal.show();

                document.getElementById('confirmResetBtn').onclick = function () {
                    // Reset form values
                    document.querySelector('input[name="name"]').value = originalValues.name;
                    document.querySelector('input[name="email"]').value = originalValues.email;
                    document.querySelector('select[name="role"]').value = originalValues.role;
                    document.querySelector('input[name="password"]').value = '';

                    // Trigger change events
                    roleSelect.dispatchEvent(new Event('change'));
                    passwordInput.dispatchEvent(new Event('input'));

                    resetModal.hide();

                    // Show success message
                    showToast('Form reset to original values', 'info');
                };
            };

            // Show toast notification
            function showToast(message, type = 'info') {
                const toast = document.createElement('div');
                toast.className = `toast align-items-center text-bg-${type} border-0`;
                toast.setAttribute('role', 'alert');
                toast.innerHTML = `
                            <div class="d-flex">
                                <div class="toast-body">
                                    ${message}
                                </div>
                                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                            </div>
                        `;

                document.body.appendChild(toast);
                const bsToast = new bootstrap.Toast(toast, { delay: 3000 });
                bsToast.show();

                toast.addEventListener('hidden.bs.toast', function () {
                    document.body.removeChild(toast);
                });
            }

            // Auto-focus first input if there's an error
            @if($errors->any())
                setTimeout(() => {
                    const firstError = document.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstError.focus();
                    }
                }, 500);
            @endif
                });
    </script>
@endsection