@extends('layouts.guest')

@section('content')
    <div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">

        <div class="card shadow-lg border-0" style="width:420px;">
            <div class="card-body p-4">

                {{-- Logo --}}
                <div class="text-center mb-4">
                    <img src="{{ asset('assets/brand/dmsys-icon-tech.svg') }}" alt="DMsys" style="height:64px">

                    <h4 class="mt-3 fw-bold">DMsys</h4>
                    <p class="text-muted small">
                        Device Maintenance Management System
                    </p>
                </div>

                {{-- Errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger small">
                        {{ $errors->first() }}
                    </div>
                @endif

                {{-- Login Form --}}
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label small" for="remember">
                                Remember me
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <a class="small" href="{{ route('password.request') }}">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-semibold">
                        Sign In
                    </button>
                </form>

            </div>
        </div>

    </div>
@endsection