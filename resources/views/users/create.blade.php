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
                                        <i class="fas fa-user-plus me-2"></i>
                                        Add New User
                                    </h4>
                                    <p class="mb-0 text-white-50">
                                        Create a new user account with appropriate permissions
                                    </p>
                                </div>
                                <a href="{{ route('users.index') }}" class="btn btn-light btn-sm">
                                    <i class="fas fa-arrow-left me-1"></i> Back to Users
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add User Form -->
            <div class="row justify-content-center mx-0">
                <div class="col-lg-8 col-md-10 px-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light py-3 border-0">
                            <h6 class="mb-0">
                                <i class="fas fa-user-circle text-primary me-2"></i>
                                User Information
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('users.store') }}" method="POST" id="addUserForm">
                                @csrf

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
                                        <input type="text" 
                                               name="name" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               placeholder="Enter user's full name"
                                               value="{{ old('name') }}"
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-text">Enter the full name of the user</div>
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
                                        <input type="email" 
                                               name="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               placeholder="Enter user's email address"
                                               value="{{ old('email') }}"
                                               required>
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
                                        Password
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-key text-muted"></i>
                                        </span>
                                        <input type="password" 
                                               name="password" 
                                               id="password"
                                               class="form-control @error('password') is-invalid @enderror" 
                                               placeholder="Create a strong password"
                                               required>
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
                                        <select name="role" 
                                                class="form-select @error('role') is-invalid @enderror"
                                                required>
                                            <option value="" disabled selected>Select a role</option>
                                            @foreach($roles as $role)
                                                @php
                                                    $roleColors = [
                                                        'admin' => 'danger',
                                                        'engineer' => 'primary', 
                                                        'technician' => 'info',
                                                        'user' => 'secondary'
                                                    ];
                                                    $color = $roleColors[$role->name] ?? 'secondary';
                                                @endphp
                                                <option value="{{ $role->name }}" 
                                                        {{ old('role') == $role->name ? 'selected' : '' }}
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
                                    <div class="form-text">Assign appropriate permissions based on role</div>
                                    
                                    <!-- Role Descriptions -->
                                    <div class="mt-3">
                                        <div class="row g-2" id="roleDescriptions">
                                            <div class="col-12">
                                                <div class="alert alert-info border-0 py-2 px-3 d-none" id="adminDesc">
                                                    <small><i class="fas fa-info-circle me-1"></i> Admin has full system access and can manage all users and settings.</small>
                                                </div>
                                                <div class="alert alert-primary border-0 py-2 px-3 d-none" id="engineerDesc">
                                                    <small><i class="fas fa-info-circle me-1"></i> Engineer can manage devices, breakdowns, and PM plans.</small>
                                                </div>
                                                <div class="alert alert-info border-0 py-2 px-3 d-none" id="technicianDesc">
                                                    <small><i class="fas fa-info-circle me-1"></i> Technician can view assigned tasks and update breakdown status.</small>
                                                </div>
                                                <div class="alert alert-secondary border-0 py-2 px-3 d-none" id="userDesc">
                                                    <small><i class="fas fa-info-circle me-1"></i> Basic user with limited viewing permissions.</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Form Actions -->
                                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary px-4">
                                        <i class="fas fa-times me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                                        <i class="fas fa-user-plus me-1"></i> Create User
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Form Tips -->
                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-body p-4">
                            <h6 class="mb-3">
                                <i class="fas fa-lightbulb text-warning me-2"></i>
                                Quick Tips
                            </h6>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <small>Ensure email is unique and valid</small>
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <small>Choose strong password for security</small>
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <small>Select appropriate role based on responsibilities</small>
                                </li>
                                <li>
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <small>User will receive email notification</small>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .dashboard-container .card {
            border-radius: 12px;
        }
        
        .input-group-text {
            border-right: none;
        }
        
        .form-control, .form-select {
            border-left: none;
            padding-left: 0;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
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
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            
            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
                });
            }
            
            // Password strength indicator
            if (passwordInput) {
                passwordInput.addEventListener('input', function() {
                    const password = this.value;
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
                roleSelect.addEventListener('change', function() {
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
                
                // Trigger change on load if there's a value
                if (roleSelect.value) {
                    roleSelect.dispatchEvent(new Event('change'));
                }
            }
            
            // Form validation
            const form = document.getElementById('addUserForm');
            const submitBtn = document.getElementById('submitBtn');
            
            if (form && submitBtn) {
                form.addEventListener('submit', function(e) {
                    // Disable submit button to prevent double submission
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Creating...';
                    
                    // Add loading class
                    submitBtn.classList.add('disabled');
                    
                    // Allow form to submit
                    return true;
                });
            }
            
            // Auto-focus first input
            const firstInput = document.querySelector('input[name="name"]');
            if (firstInput) {
                setTimeout(() => {
                    firstInput.focus();
                }, 100);
            }
            
            // Show error messages if any
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