@extends('layouts.app')

@section('title', 'Edit Case ' . $case->case_number)

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-9 col-lg-10">
        <!-- Back Navigation & Title -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="{{ route('cases.show', $case) }}" class="text-decoration-none text-muted small d-inline-flex align-items-center gap-1 mb-2">
                    <i class="bi bi-arrow-left"></i> Back to Case Details
                </a>
                <h2 class="fw-bold text-dark mb-0 tracking-tight">Edit Case [{{ $case->case_number }}]</h2>
                <p class="text-muted small mb-0">Update surgical details, anatomical material, and workflow progression.</p>
            </div>
            <div>
                <span class="status-pill status-{{ Str::slug($case->status) }}">
                    <span class="dot"></span>
                    {{ $case->status }}
                </span>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card card-clean p-4 p-md-5">
            <form method="POST" action="{{ route('cases.update', $case) }}" novalidate>
                @csrf
                @method('PUT')
                @include('cases._form')
            </form>
        </div>
    </div>
</div>
@endsection
