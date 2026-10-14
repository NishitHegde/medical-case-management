@extends('layouts.app')

@section('title', 'Session Expired (419)')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="col-md-7 col-lg-5 text-center">
        <div class="card card-clean p-5 shadow-lg border-0 rounded-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning rounded-circle mx-auto mb-4" style="width: 80px; height: 80px;">
                <i class="bi bi-shield-exclamation fs-1"></i>
            </div>
            <h3 class="fw-bold text-dark mb-2">Security Session Expired</h3>
            <p class="text-muted small mb-4" style="line-height: 1.6;">
                For your patient privacy and HIPAA clinical data protection, your session timed out after a period of inactivity. Please refresh and log back in.
            </p>
            <div class="d-grid gap-2">
                <a href="{{ route('login') }}" class="btn btn-jm-primary py-2 fw-semibold">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Return to Login Screen
                </a>
                <button type="button" onclick="window.location.reload()" class="btn btn-jm-secondary py-2">
                    <i class="bi bi-arrow-clockwise me-1"></i> Refresh Current Page
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
