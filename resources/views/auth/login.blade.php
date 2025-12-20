@extends('layouts.guest')

@section('content')
    <div class="min-vh-100 d-flex align-items-center justify-content-center bg-gradient-primary">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-5 col-lg-4">
                    <!-- Card Container -->
                    <div class="card border-0 shadow-lg overflow-hidden animate__animated animate__fadeInUp">
                        <!-- Header with Gradient -->
                        <div class="card-header bg-gradient-primary text-white text-center py-4 border-0 position-relative">
                            <!-- Background Pattern -->
                            <div class="position-absolute top-0 end-0 w-100 h-100 opacity-10">
                                <div class="pattern-dots"></div>
                            </div>
                            
                            <!-- Logo -->
                            <div class="position-relative z-1">
                                <div class="logo-container mb-3">
                                    <img src="{{ asset('assets/brand/dmsys-icon-tech.svg') }}" 
                                         alt="DMsys" 
                                         class="logo-img animate__animated animate__pulse">
                                </div>
                                <h3 class="fw-bold mb-1">DMsys</h3>
                                <p class="small opacity-75 mb-0">
                                    Device Maintenance Management System
                                </p>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body p-4 p-lg-5">
                            <!-- Welcome Message -->
                            <div class="text-center mb-4">
                                <h5 class="fw-bold text-dark mb-2">Welcome Back</h5>
                                <p class="text-muted small mb-0">
                                    Sign in to continue to your dashboard
                                </p>
                            </div>

                            <!-- Error Messages -->
                            @if ($errors->any())
                                <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger d-flex align-items-center animate__animated animate__shakeX">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    <div class="flex-grow-1">{{ $errors->first() }}</div>
                                </div>
                            @endif

                            <!-- Success Messages -->
                            @if (session('status'))
                                <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success d-flex align-items-center">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <div class="flex-grow-1">{{ session('status') }}</div>
                                </div>
                            @endif

                            <!-- Login Form -->
                            <form method="POST" action="{{ route('login') }}" id="loginForm">
                                @csrf

                                <!-- Email Field -->
                                <div class="mb-4">
                                    <label class="form-label fw-medium text-dark mb-2">
                                        <i class="fas fa-envelope me-1 text-primary"></i>
                                        Email Address
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-user text-muted"></i>
                                        </span>
                                        <input type="email" 
                                               name="email" 
                                               class="form-control border-start-0 ps-0"
                                               placeholder="Enter your email"
                                               value="{{ old('email') }}"
                                               required 
                                               autofocus
                                               autocomplete="email">
                                    </div>
                                </div>

                                <!-- Password Field -->
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label class="form-label fw-medium text-dark mb-0">
                                            <i class="fas fa-lock me-1 text-primary"></i>
                                            Password
                                        </label>
                                        @if (Route::has('password.request'))
                                            <a href="{{ route('password.request') }}" 
                                               class="small text-decoration-none text-primary">
                                                Forgot password?
                                            </a>
                                        @endif
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-key text-muted"></i>
                                        </span>
                                        <input type="password" 
                                               name="password" 
                                               id="password"
                                               class="form-control border-start-0 ps-0"
                                               placeholder="Enter your password"
                                               required
                                               autocomplete="current-password">
                                        <button type="button" 
                                                class="btn btn-outline-secondary border-start-0"
                                                id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Remember Me & Options -->
                                <div class="mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="remember" 
                                               id="remember" 
                                               {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label text-muted small" for="remember">
                                            <i class="fas fa-check-circle me-1"></i>
                                            Keep me signed in
                                        </label>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" 
                                        class="btn btn-primary btn-gradient w-100 fw-semibold py-2 mb-3"
                                        id="loginButton">
                                    <i class="fas fa-sign-in-alt me-2"></i>
                                    Sign In
                                    <span class="spinner-border spinner-border-sm ms-2 d-none" 
                                          id="loginSpinner"></span>
                                </button>

                                <!-- Admin Notice -->
                                <div class="alert alert-info border-0 bg-info bg-opacity-10 text-info small mt-4">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-info-circle mt-1 me-2"></i>
                                        <div>
                                            <strong>System Access:</strong> 
                                            User accounts are managed by system administrators. 
                                            Contact your administrator for access requests.
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Footer -->
                        <div class="card-footer bg-transparent border-0 text-center py-3">
                            <p class="small text-muted mb-0">
                                <i class="fas fa-shield-alt me-1"></i>
                                Secure login protected by encryption
                            </p>
                        </div>
                    </div>

                    <!-- System Status -->
                    <div class="text-center mt-4">
                        <div class="d-inline-flex align-items-center bg-white rounded-pill px-3 py-1 shadow-sm">
                            <div class="status-indicator bg-success me-2"></div>
                            <span class="small text-muted">System Status: <strong>Online</strong></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .bg-gradient-primary {
            background: var(--primary-gradient) !important;
        }

        .btn-gradient {
            background: var(--primary-gradient);
            border: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-gradient:active {
            transform: translateY(0);
        }

        .btn-gradient::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }

        .btn-gradient:hover::after {
            left: 100%;
        }

        .logo-container {
            position: relative;
            width: 80px;
            height: 80px;
            margin: 0 auto;
            background: white;
            border-radius: 50%;
            padding: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .logo-img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }

        .pattern-dots {
            background-image: radial-gradient(rgba(255,255,255,0.3) 1px, transparent 1px);
            background-size: 20px 20px;
            width: 100%;
            height: 100%;
        }

        .status-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        .input-group:focus-within {
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            border-radius: 8px;
        }

        .input-group:focus-within .input-group-text {
            border-color: #667eea;
            background-color: rgba(102, 126, 234, 0.05);
        }

        .input-group:focus-within input {
            border-color: #667eea;
        }

        .form-control {
            border-left: none;
            padding-left: 0;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #dee2e6;
        }

        .alert {
            border-radius: 10px;
            backdrop-filter: blur(10px);
        }

        .card {
            border-radius: 20px;
            overflow: hidden;
        }

        .card-header {
            border-top-left-radius: 20px !important;
            border-top-right-radius: 20px !important;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(40, 167, 69, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(40, 167, 69, 0);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .card {
                width: 90%;
                margin: 0 auto;
            }
            
            .logo-container {
                width: 70px;
                height: 70px;
                padding: 12px;
            }
        }

        @media (max-width: 576px) {
            .card-body {
                padding: 1.5rem !important;
            }
            
            .btn-gradient {
                padding: 0.75rem 1.5rem;
            }
        }

        /* Dark Mode Support */
        @media (prefers-color-scheme: dark) {
            .card {
                background: #1a1a1a;
                color: #f8f9fa;
            }
            
            .card-header {
                background: #2d2d2d !important;
            }
            
            .form-control, .input-group-text {
                background: #2d2d2d;
                border-color: #404040;
                color: #f8f9fa;
            }
            
            .form-control::placeholder {
                color: #6c757d;
            }
            
            .text-dark {
                color: #f8f9fa !important;
            }
            
            .text-muted {
                color: #adb5bd !important;
            }
            
            .bg-light {
                background: #2d2d2d !important;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password visibility toggle
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            
            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.innerHTML = type === 'password' ? 
                        '<i class="fas fa-eye"></i>' : 
                        '<i class="fas fa-eye-slash"></i>';
                });
            }

            // Form submission animation
            const loginForm = document.getElementById('loginForm');
            const loginButton = document.getElementById('loginButton');
            const loginSpinner = document.getElementById('loginSpinner');
            
            if (loginForm && loginButton && loginSpinner) {
                loginForm.addEventListener('submit', function() {
                    loginButton.disabled = true;
                    loginSpinner.classList.remove('d-none');
                    loginButton.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i>Signing In...';
                });
            }

            // Input focus effects
            const inputs = document.querySelectorAll('input[type="email"], input[type="password"]');
            inputs.forEach(input => {
                const parent = input.closest('.input-group');
                
                input.addEventListener('focus', function() {
                    if (parent) {
                        parent.style.boxShadow = '0 0 0 3px rgba(102, 126, 234, 0.1)';
                    }
                });
                
                input.addEventListener('blur', function() {
                    if (parent) {
                        parent.style.boxShadow = 'none';
                    }
                });
            });

            // Add floating animation to logo
            const logo = document.querySelector('.logo-img');
            if (logo) {
                logo.classList.add('animate-float');
            }

            // Auto-focus email field if empty
            const emailInput = document.querySelector('input[type="email"]');
            if (emailInput && !emailInput.value) {
                emailInput.focus();
            }
        });
    </script>
@endsection