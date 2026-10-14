@extends('layouts.app')

@section('title', 'Log New Medical Case')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-9 col-lg-10">
        <!-- Back Navigation & Title -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="{{ route('cases.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center gap-1 mb-2">
                    <i class="bi bi-arrow-left"></i> Back to Cases
                </a>
                <h2 class="fw-bold text-dark mb-0 tracking-tight">Log New Medical Implant Case</h2>
                <p class="text-muted small mb-0">Record surgical specifications, anatomical material, and scheduling.</p>
            </div>
            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2 rounded-pill small">
                Case Intake Workflow
            </span>
        </div>

        <!-- Form Card -->
        <div class="card card-clean p-4 p-md-5">
            <form method="POST" action="{{ route('cases.store') }}" novalidate>
                @csrf
                @include('cases._form')
            </form>
        </div>
    </div>
</div>
@endsection
