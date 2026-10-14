@extends('layouts.app')

@section('title', 'Log New Medical Case')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <a href="{{ route('cases.index') }}" class="text-decoration-none text-muted small">
                    <i class="bi bi-arrow-left"></i> Back to Cases
                </a>
                <h3 class="fw-bold mt-1 mb-0">Log New Medical Implant Case</h3>
            </div>
            <span class="badge bg-primary px-3 py-2">Creation Workflow</span>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('cases.store') }}" novalidate>
                    @csrf
                    @include('cases._form')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
