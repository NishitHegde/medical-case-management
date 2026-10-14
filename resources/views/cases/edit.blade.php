@extends('layouts.app')

@section('title', 'Edit Case ' . $case->case_number)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <a href="{{ route('cases.show', $case) }}" class="text-decoration-none text-muted small">
                    <i class="bi bi-arrow-left"></i> Back to Case Details
                </a>
                <h3 class="fw-bold mt-1 mb-0">Edit Medical Case [{{ $case->case_number }}]</h3>
            </div>
            <span class="badge rounded-pill badge-status-{{ Str::slug($case->status) }} px-3 py-2">
                {{ $case->status }}
            </span>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('cases.update', $case) }}" novalidate>
                    @csrf
                    @method('PUT')
                    @include('cases._form')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
