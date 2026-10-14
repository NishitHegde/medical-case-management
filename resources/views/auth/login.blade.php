@extends('layouts.app')

@section('title', 'Sign In')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 75vh;">
    <div class="col-md-6 col-lg-5 col-xl-4">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle mb-3" style="width: 56px; height: 56px;">
                        <i class="bi bi-hospital fs-2"></i>
                    </div>
                    <h3 class="fw-bold text-dark">Jajal Medical</h3>
                    <p class="text-muted small">Medical Implant Case Management System</p>
                </div>

                @if(session('info'))
                    <div class="alert alert-info py-2 small mb-3">
                        <i class="bi bi-info-circle me-1"></i> {{ session('info') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" novalidate>
                    @csrf

                    <!-- Email Field -->
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold small text-secondary">Work Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input id="email" type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email', 'admin@jajalmedical.com') }}" required autocomplete="email" autofocus placeholder="name@jajalmedical.com">
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1">
                                <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold small text-secondary">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input id="password" type="password" class="form-control border-start-0 @error('password') is-invalid @enderror" 
                                   name="password" value="Password123!" required autocomplete="current-password" placeholder="Enter password">
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">
                                <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Remember Me Checkbox -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label small text-secondary" for="remember">
                                Remember this device
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid mb-4">
                        <button type="submit" class="btn btn-primary py-2 fw-semibold">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Sign In to Portal
                        </button>
                    </div>

                    <!-- Demo Credentials Helper Box for Reviewers -->
                    <div class="p-3 bg-light rounded-3 border small">
                        <div class="fw-bold text-secondary mb-2 d-flex align-items-center justify-content-between">
                            <span><i class="bi bi-key me-1"></i> Demo Credentials</span>
                            <span class="badge bg-secondary">Testing</span>
                        </div>
                        <div class="mb-2">
                            <span class="fw-semibold text-danger">Admin:</span>
                            <code>admin@jajalmedical.com</code> &bull; <code>Password123!</code>
                            <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 float-end" onclick="fillCreds('admin@jajalmedical.com', 'Password123!')">Use</button>
                        </div>
                        <div class="mb-2">
                            <span class="fw-semibold text-primary">Viewer:</span>
                            <code>viewer@jajalmedical.com</code> &bull; <code>Password123!</code>
                            <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2 float-end" onclick="fillCreds('viewer@jajalmedical.com', 'Password123!')">Use</button>
                        </div>
                        <div>
                            <span class="fw-semibold text-warning">Engineer:</span>
                            <code>engineer@jajalmedical.com</code> &bull; <code>Password123!</code>
                            <button type="button" class="btn btn-sm btn-outline-warning py-0 px-2 float-end" onclick="fillCreds('engineer@jajalmedical.com', 'Password123!')">Use</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function fillCreds(email, password) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = password;
}
</script>
@endsection
