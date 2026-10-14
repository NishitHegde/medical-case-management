@extends('layouts.app')

@section('title', 'Sign In')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-xl-9 col-lg-11">
        <div class="card card-clean border-0 shadow-lg overflow-hidden rounded-4">
            <div class="row g-0">
                <!-- Left Pane: Medical Branding & Highlights -->
                <div class="col-lg-5 d-none d-lg-flex flex-column justify-content-between p-5 text-white" 
                     style="background: linear-gradient(145deg, #0f172a 0%, #1e293b 60%, #1e40af 100%); position: relative; overflow: hidden;">
                    
                    <!-- Subtle background medical cross pattern -->
                    <div style="position: absolute; right: -40px; bottom: -40px; opacity: 0.05; font-size: 280px; pointer-events: none;">
                        <i class="bi bi-hospital"></i>
                    </div>

                    <div style="position: relative; z-index: 1;">
                        <div class="d-inline-flex align-items-center gap-2 mb-4 bg-white bg-opacity-10 px-3 py-1 rounded-pill border border-white border-opacity-10 small">
                            <span class="brand-logo-badge" style="width: 26px; height: 26px; font-size: 0.9rem;">
                                <i class="bi bi-hospital"></i>
                            </span>
                            <span class="fw-semibold">Jajal Medical</span>
                        </div>
                        <h2 class="fw-bold tracking-tight mb-3 text-white" style="font-size: 1.85rem; line-height: 1.25;">
                            Patient-Specific Implant Case Management
                        </h2>
                        <p class="text-white-50 small mb-0" style="line-height: 1.6;">
                            Enterprise clinical tracking for cranial, mandibular, orthopaedic, and spinal implants from digital design to hospital delivery.
                        </p>
                    </div>

                    <div class="my-5" style="position: relative; z-index: 1;">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="bg-primary bg-opacity-25 p-2 rounded-3 text-info">
                                <i class="bi bi-shield-check fs-5"></i>
                            </div>
                            <div>
                                <div class="fw-semibold text-white small">Role-Based Access Control</div>
                                <div class="text-white-50" style="font-size: 0.78rem;">Rigid policy authorization for Admins, Engineers, and Viewers.</div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="bg-primary bg-opacity-25 p-2 rounded-3 text-info">
                                <i class="bi bi-cpu fs-5"></i>
                            </div>
                            <div>
                                <div class="fw-semibold text-white small">Sanctum REST API</div>
                                <div class="text-white-50" style="font-size: 0.78rem;">Full personal access token authentication and CRUD endpoints.</div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary bg-opacity-25 p-2 rounded-3 text-info">
                                <i class="bi bi-calendar-week fs-5"></i>
                            </div>
                            <div>
                                <div class="fw-semibold text-white small">Surgery Conflict Prevention</div>
                                <div class="text-white-50" style="font-size: 0.78rem;">Automated Public Holiday API synchronization.</div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-3 border-top border-white border-opacity-10 d-flex justify-content-between align-items-center text-white-50 small" style="position: relative; z-index: 1; font-size: 0.78rem;">
                        <span>&copy; {{ date('Y') }} Jajal Medical Inc.</span>
                        <span>Clinical Portal v2.0</span>
                    </div>
                </div>

                <!-- Right Pane: Login Form -->
                <div class="col-lg-7 p-4 p-sm-5 bg-white d-flex flex-column justify-content-center">
                    <div class="mb-4">
                        <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold px-2 py-1 rounded-pill small mb-2">
                            Secure Authentication
                        </span>
                        <h3 class="fw-bold text-dark mb-1">Sign In to Case Portal</h3>
                        <p class="text-muted small">Enter your clinical credentials or pick a demo persona below.</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}" novalidate>
                        @csrf

                        <!-- Email Input -->
                        <div class="mb-3">
                            <label for="email" class="form-label small fw-bold text-secondary text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Work Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0 rounded-start-3">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input id="email" type="email" class="form-control rounded-end-3 border-start-0 py-2 @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email', 'admin@jajalmedical.com') }}" required autocomplete="email" autofocus placeholder="name@jajalmedical.com">
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1 d-flex align-items-center gap-1">
                                    <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Password Input -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="password" class="form-label small fw-bold text-secondary text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Password</label>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0 rounded-start-3">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input id="password" type="password" class="form-control border-start-0 border-end-0 py-2 @error('password') is-invalid @enderror" 
                                       name="password" value="Password123!" required autocomplete="current-password" placeholder="Enter your password">
                                <button type="button" class="input-group-text bg-light text-muted border-start-0 rounded-end-3" onclick="togglePasswordVisibility()">
                                    <i class="bi bi-eye" id="togglePasswordIcon"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1 d-flex align-items-center gap-1">
                                    <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Remember Device -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember', true) ? 'checked' : '' }}>
                                <label class="form-check-label small text-muted" for="remember">
                                    Keep session active on this workstation
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-jm-primary py-2 fs-6">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Sign In to Clinical System
                            </button>
                        </div>

                        <!-- Quick-Switch Demo Role Chips -->
                        <div class="p-3 rounded-3 border bg-light">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="small fw-bold text-secondary text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.05em;">
                                    <i class="bi bi-lightning-charge-fill text-warning me-1"></i> One-Click Demo Personas
                                </span>
                                <span class="badge bg-secondary bg-opacity-25 text-secondary" style="font-size: 0.65rem;">Testing Accounts</span>
                            </div>

                            <div class="row g-2">
                                <div class="col-sm-4">
                                    <button type="button" class="btn btn-outline-danger w-100 text-start p-2 rounded-3 bg-white" onclick="fillPersona('admin@jajalmedical.com', 'Password123!')">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="fw-bold small text-danger">Admin</span>
                                            <i class="bi bi-shield-fill text-danger small"></i>
                                        </div>
                                        <div class="text-muted" style="font-size: 0.68rem;">Full CRUD Access</div>
                                    </button>
                                </div>

                                <div class="col-sm-4">
                                    <button type="button" class="btn btn-outline-primary w-100 text-start p-2 rounded-3 bg-white" onclick="fillPersona('viewer@jajalmedical.com', 'Password123!')">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="fw-bold small text-primary">Viewer</span>
                                            <i class="bi bi-eye-fill text-primary small"></i>
                                        </div>
                                        <div class="text-muted" style="font-size: 0.68rem;">Read-Only Mode</div>
                                    </button>
                                </div>

                                <div class="col-sm-4">
                                    <button type="button" class="btn btn-outline-warning w-100 text-start p-2 rounded-3 bg-white" onclick="fillPersona('engineer@jajalmedical.com', 'Password123!')">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="fw-bold small text-warning-emphasis">Engineer</span>
                                            <i class="bi bi-gear-fill text-warning small"></i>
                                        </div>
                                        <div class="text-muted" style="font-size: 0.68rem;">Create &amp; Edit Only</div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function fillPersona(email, password) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = password;
}

function togglePasswordVisibility() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('togglePasswordIcon');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('bi-eye');
        toggleIcon.classList.add('bi-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('bi-eye-slash');
        toggleIcon.classList.add('bi-eye');
    }
}
</script>
@endsection
