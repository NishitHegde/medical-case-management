@extends('layouts.app')

@section('title', 'Case Details - ' . $case->case_number)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-9">
        <!-- Header & Action Buttons -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
            <div>
                <a href="{{ route('cases.index') }}" class="text-decoration-none text-muted small">
                    <i class="bi bi-arrow-left"></i> Back to Medical Cases
                </a>
                <h2 class="fw-bold mt-1 mb-0 d-flex align-items-center gap-2">
                    <span>Case #{{ $case->case_number }}</span>
                    <span class="badge rounded-pill badge-status-{{ Str::slug($case->status) }} fs-6">
                        {{ $case->status }}
                    </span>
                    <span class="badge rounded-pill badge-priority-{{ Str::slug($case->priority) }} fs-6">
                        {{ $case->priority }} Priority
                    </span>
                </h2>
            </div>
            <div class="d-flex gap-2">
                @can('update', $case)
                <a href="{{ route('cases.edit', $case) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-1"></i> Edit Case
                </a>
                @endcan

                @can('delete', $case)
                <button type="button" class="btn btn-outline-danger" onclick="confirmDelete('{{ $case->id }}', '{{ $case->case_number }}')">
                    <i class="bi bi-trash me-1"></i> Delete
                </button>
                @endcan
            </div>
        </div>

        @if($surgeryHoliday)
        <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center mb-4">
            <i class="bi bi-exclamation-triangle-fill fs-4 me-3 text-warning"></i>
            <div>
                <strong>Notice: Scheduled Surgery Date Coincides with Public Holiday!</strong>
                <div>{{ $case->surgery_date->format('F d, Y') }} is recognized as <strong>{{ $surgeryHoliday }}</strong>. Please confirm hospital surgical theater availability.</div>
            </div>
        </div>
        @endif

        <!-- Case Details Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0 text-primary">
                    <i class="bi bi-hospital me-2"></i> Clinical Case Information
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <!-- ID -->
                    <div class="col-md-4">
                        <div class="text-secondary small fw-semibold text-uppercase">System Record ID</div>
                        <div class="fs-5 fw-bold text-dark font-monospace">#{{ $case->id }}</div>
                    </div>

                    <!-- Case Number -->
                    <div class="col-md-4">
                        <div class="text-secondary small fw-semibold text-uppercase">Case Number</div>
                        <div class="fs-5 fw-bold text-primary font-monospace">{{ $case->case_number }}</div>
                    </div>

                    <!-- Patient Reference -->
                    <div class="col-md-4">
                        <div class="text-secondary small fw-semibold text-uppercase">Patient Reference Code</div>
                        <div class="fs-5 fw-bold font-monospace text-dark">{{ $case->patient_reference }}</div>
                        <div class="small text-muted">De-identified clinical dummy code</div>
                    </div>

                    <!-- Surgeon Name -->
                    <div class="col-md-6">
                        <div class="text-secondary small fw-semibold text-uppercase">Attending Surgeon</div>
                        <div class="fs-5 fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-person-fill text-muted"></i>
                            {{ $case->surgeon_name }}
                        </div>
                    </div>

                    <!-- Implant Type -->
                    <div class="col-md-6">
                        <div class="text-secondary small fw-semibold text-uppercase">Implant Type / Specification</div>
                        <div class="fs-5 fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-gear-wide-connected text-muted"></i>
                            {{ $case->implant_type }}
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-4">
                        <div class="text-secondary small fw-semibold text-uppercase">Current Status</div>
                        <div class="mt-1">
                            <span class="badge rounded-pill badge-status-{{ Str::slug($case->status) }} px-3 py-2 fs-6">
                                {{ $case->status }}
                            </span>
                        </div>
                    </div>

                    <!-- Priority -->
                    <div class="col-md-4">
                        <div class="text-secondary small fw-semibold text-uppercase">Clinical Priority</div>
                        <div class="mt-1">
                            <span class="badge rounded-pill badge-priority-{{ Str::slug($case->priority) }} px-3 py-2 fs-6">
                                {{ $case->priority }}
                            </span>
                        </div>
                    </div>

                    <!-- Surgery Date -->
                    <div class="col-md-4">
                        <div class="text-secondary small fw-semibold text-uppercase">Surgery Date</div>
                        <div class="fs-5 fw-bold text-dark mt-1">
                            @if($case->surgery_date)
                                <i class="bi bi-calendar-check text-primary me-1"></i>
                                {{ $case->surgery_date->format('F d, Y') }}
                            @else
                                <span class="text-muted fst-italic">Not scheduled</span>
                            @endif
                        </div>
                    </div>
                </div>

                <hr class="my-4 text-muted">

                <!-- Audit and Metadata -->
                <div class="row g-3 text-muted small">
                    <div class="col-md-4">
                        <span class="fw-semibold">Created By:</span>
                        <div class="text-dark fw-medium mt-1">
                            <i class="bi bi-person-circle me-1"></i>
                            {{ $case->creator?->name ?? 'System' }} 
                            <span class="badge bg-light text-secondary border">
                                {{ ucfirst($case->creator?->role ?? 'user') }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <span class="fw-semibold">Record Created At:</span>
                        <div class="text-dark fw-medium mt-1">
                            <i class="bi bi-clock me-1"></i>
                            {{ $case->created_at->format('M d, Y h:i A') }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <span class="fw-semibold">Last Updated At:</span>
                        <div class="text-dark fw-medium mt-1">
                            <i class="bi bi-arrow-repeat me-1"></i>
                            {{ $case->updated_at->format('M d, Y h:i A') }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-light py-3 d-flex justify-content-between align-items-center">
                <a href="{{ route('cases.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Return to Case List
                </a>
                @can('update', $case)
                <a href="{{ route('cases.edit', $case) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-1"></i> Edit This Case
                </a>
                @endcan
            </div>
        </div>
    </div>
</div>

<!-- Hidden Delete Form -->
<form id="delete-case-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold" id="deleteConfirmModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Confirm Case Deletion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p>Are you sure you want to delete medical case <strong id="modal-case-number" class="text-danger font-monospace"></strong>?</p>
                <div class="alert alert-warning small mb-0">
                    <i class="bi bi-info-circle me-1"></i> This record will be permanently purged from the database.
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger fw-semibold" id="confirmDeleteButton">
                    <i class="bi bi-trash me-1"></i> Confirm Delete
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let deleteCaseId = null;
const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));

function confirmDelete(caseId, caseNumber) {
    deleteCaseId = caseId;
    document.getElementById('modal-case-number').textContent = caseNumber;
    deleteModal.show();
}

document.getElementById('confirmDeleteButton').addEventListener('click', function() {
    if (deleteCaseId) {
        const form = document.getElementById('delete-case-form');
        form.action = `/cases/${deleteCaseId}`;
        form.submit();
    }
});
</script>
@endsection
