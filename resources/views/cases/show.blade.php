@extends('layouts.app')

@section('title', 'Case Details - ' . $case->case_number)

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-10 col-lg-11">
        <!-- Top Navigation & Action Controls -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div>
                <a href="{{ route('cases.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center gap-1 mb-2">
                    <i class="bi bi-arrow-left"></i> Return to Case Listing
                </a>
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <h2 class="fw-bold text-dark mb-0 font-mono tracking-tight">{{ $case->case_number }}</h2>
                    <span class="status-pill status-{{ Str::slug($case->status) }}">
                        <span class="dot"></span>
                        {{ $case->status }}
                    </span>
                    <span class="priority-badge priority-{{ Str::slug($case->priority) }}">
                        {{ $case->priority }} Priority
                    </span>
                </div>
            </div>

            <!-- Action Controls -->
            <div class="d-flex gap-2">
                @can('update', $case)
                <a href="{{ route('cases.edit', $case) }}" class="btn btn-jm-primary d-inline-flex align-items-center gap-1">
                    <i class="bi bi-pencil"></i> Edit Case
                </a>
                @endcan

                @can('delete', $case)
                <button type="button" class="btn btn-outline-danger d-inline-flex align-items-center gap-1 rounded-3" 
                        onclick="confirmDelete('{{ $case->id }}', '{{ $case->case_number }}')">
                    <i class="bi bi-trash"></i> Delete
                </button>
                @endcan
            </div>
        </div>

        <!-- Public Holiday Conflict Warning -->
        @if($surgeryHoliday)
        <div class="alert alert-warning border-0 shadow-sm rounded-4 p-4 mb-4 d-flex align-items-start gap-3" style="background-color: #fffbeb;">
            <div class="bg-warning text-white rounded-3 p-2 mt-1">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            </div>
            <div>
                <h6 class="fw-bold text-warning-emphasis mb-1">Notice: Surgery Date Coincides with Recognized Public Holiday</h6>
                <p class="mb-0 small text-warning-emphasis" style="line-height: 1.5;">
                    The scheduled date of <strong>{{ $case->surgery_date->format('F d, Y') }}</strong> is recognized as <strong>{{ $surgeryHoliday }}</strong>. 
                    Please coordinate with surgical theater scheduling to confirm hospital facility staffing and sterilization availability.
                </p>
            </div>
        </div>
        @endif

        <!-- Visual Clinical Workflow Stepper -->
        @php
            $stages = ['Draft', 'Planning', 'In Review', 'Approved', 'Completed'];
            $currentIndex = array_search($case->status, $stages);
            if ($currentIndex === false && $case->status === 'Cancelled') {
                $currentIndex = -1;
            }
        @endphp

        <div class="card card-clean mb-4 p-4">
            <div class="small fw-bold text-secondary text-uppercase mb-3" style="font-size: 0.72rem; letter-spacing: 0.05em;">
                Clinical Workflow Progress
            </div>
            <div class="d-flex justify-content-between position-relative px-2">
                @foreach($stages as $index => $stage)
                    @php
                        $isCompleted = ($currentIndex !== false && $index < $currentIndex) || ($case->status === 'Completed');
                        $isCurrent = ($case->status === $stage);
                    @endphp
                    <div class="text-center position-relative" style="z-index: 1; flex: 1;">
                        <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center shadow-sm fw-bold small mb-2" 
                             style="width: 32px; height: 32px; 
                             background-color: {{ $isCurrent ? '#2563eb' : ($isCompleted ? '#10b981' : '#f1f5f9') }}; 
                             color: {{ ($isCurrent || $isCompleted) ? '#ffffff' : '#94a3b8' }};">
                            @if($isCompleted)
                                <i class="bi bi-check-lg"></i>
                            @else
                                {{ $index + 1 }}
                            @endif
                        </div>
                        <div class="small fw-semibold {{ $isCurrent ? 'text-primary' : ($isCompleted ? 'text-dark' : 'text-muted') }}" style="font-size: 0.78rem;">
                            {{ $stage }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Main Details Grid -->
        <div class="row g-4">
            <!-- Left Column: Clinical Dossier -->
            <div class="col-lg-8">
                <div class="card card-clean p-4 p-md-5 h-100">
                    <h5 class="fw-bold text-dark mb-4 d-flex align-items-center gap-2 pb-3 border-bottom">
                        <i class="bi bi-clipboard2-pulse text-primary"></i>
                        Clinical Case Specifications
                    </h5>

                    <div class="row g-4 mb-4">
                        <!-- Patient Reference -->
                        <div class="col-sm-6">
                            <div class="small fw-bold text-secondary text-uppercase mb-1" style="font-size: 0.72rem; letter-spacing: 0.05em;">
                                Patient Reference Code
                            </div>
                            <div class="fs-5 fw-bold font-mono text-dark">
                                {{ $case->patient_reference }}
                            </div>
                            <div class="text-muted" style="font-size: 0.75rem;">De-identified dummy code (HIPAA safe)</div>
                        </div>

                        <!-- System Record ID -->
                        <div class="col-sm-6">
                            <div class="small fw-bold text-secondary text-uppercase mb-1" style="font-size: 0.72rem; letter-spacing: 0.05em;">
                                Internal Database Record ID
                            </div>
                            <div class="fs-5 fw-bold font-mono text-secondary">
                                #{{ $case->id }}
                            </div>
                        </div>

                        <!-- Surgeon Name -->
                        <div class="col-sm-6">
                            <div class="small fw-bold text-secondary text-uppercase mb-1" style="font-size: 0.72rem; letter-spacing: 0.05em;">
                                Attending Surgical Specialist
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-1">
                                <div class="user-avatar-circle" style="width: 32px; height: 32px; font-size: 0.8rem; background: #3b82f6;">
                                    {{ strtoupper(substr($case->surgeon_name, 3, 1)) }}
                                </div>
                                <div class="fw-bold text-dark fs-6">{{ $case->surgeon_name }}</div>
                            </div>
                        </div>

                        <!-- Implant Specification -->
                        <div class="col-sm-6">
                            <div class="small fw-bold text-secondary text-uppercase mb-1" style="font-size: 0.72rem; letter-spacing: 0.05em;">
                                Implant Specification &amp; Material
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-1">
                                <div class="p-2 bg-light rounded-3 text-primary">
                                    <i class="bi bi-gear-wide-connected"></i>
                                </div>
                                <div class="fw-bold text-dark fs-6">{{ $case->implant_type }}</div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4 text-muted">

                    <!-- Audit Trail & System Log -->
                    <div class="small fw-bold text-secondary text-uppercase mb-3" style="font-size: 0.72rem; letter-spacing: 0.05em;">
                        System Audit Trail &amp; Authorship
                    </div>

                    <div class="row g-3 small text-muted">
                        <div class="col-md-4">
                            <div class="fw-semibold text-secondary">Created By</div>
                            <div class="text-dark fw-bold mt-1 d-flex align-items-center gap-1">
                                <i class="bi bi-person-fill text-muted"></i>
                                {{ $case->creator?->name ?? 'System' }}
                                <span class="role-pill role-pill-{{ $case->creator?->role ?? 'viewer' }} ms-1" style="font-size: 0.65rem;">
                                    {{ strtoupper($case->creator?->role ?? 'user') }}
                                </span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="fw-semibold text-secondary">Created Timestamp</div>
                            <div class="text-dark font-mono mt-1">
                                {{ $case->created_at->format('M d, Y h:i A') }}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="fw-semibold text-secondary">Last Modified</div>
                            <div class="text-dark font-mono mt-1">
                                {{ $case->updated_at->format('M d, Y h:i A') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Scheduling & Quick Actions -->
            <div class="col-lg-4">
                <!-- Scheduling Card -->
                <div class="card card-clean p-4 mb-4">
                    <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-calendar3 text-primary"></i> Surgical Scheduling
                    </h6>

                    <div class="p-3 bg-light rounded-3 border mb-3">
                        <div class="small text-muted fw-semibold mb-1">Target Implantation Date</div>
                        <div class="fs-4 fw-bold text-dark font-mono">
                            @if($case->surgery_date)
                                {{ $case->surgery_date->format('M d, Y') }}
                            @else
                                <span class="text-muted fs-6 fst-italic">No date scheduled</span>
                            @endif
                        </div>
                        @if($case->surgery_date)
                            <div class="small text-muted mt-1">
                                {{ $case->surgery_date->diffForHumans() }}
                            </div>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between align-items-center small text-muted border-top pt-2">
                        <span>Workflow Phase:</span>
                        <span class="status-pill status-{{ Str::slug($case->status) }}" style="font-size: 0.7rem;">
                            {{ $case->status }}
                        </span>
                    </div>
                </div>

                <!-- Case Actions Card -->
                <div class="card card-clean p-4">
                    <h6 class="fw-bold text-dark mb-3">Record Management</h6>
                    <div class="d-grid gap-2">
                        @can('update', $case)
                        <a href="{{ route('cases.edit', $case) }}" class="btn btn-jm-secondary text-start d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-pencil me-2 text-primary"></i> Edit Specifications</span>
                            <i class="bi bi-chevron-right text-muted small"></i>
                        </a>
                        @endcan

                        <a href="{{ route('cases.index') }}" class="btn btn-jm-secondary text-start d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-folder2-open me-2 text-secondary"></i> All Cases List</span>
                            <i class="bi bi-chevron-right text-muted small"></i>
                        </a>

                        @can('delete', $case)
                        <button type="button" class="btn btn-outline-danger text-start d-flex justify-content-between align-items-center rounded-3 mt-2" 
                                onclick="confirmDelete('{{ $case->id }}', '{{ $case->case_number }}')">
                            <span><i class="bi bi-trash me-2"></i> Delete This Case</span>
                            <i class="bi bi-exclamation-circle small"></i>
                        </button>
                        @endcan
                    </div>
                </div>
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
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-danger text-white py-3 px-4">
                <h5 class="modal-title fw-bold" id="deleteConfirmModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Confirm Case Deletion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-dark mb-2">Are you sure you want to permanently delete case <strong id="modal-case-number" class="text-danger font-mono"></strong>?</p>
                <div class="p-3 bg-light rounded-3 border small text-muted">
                    <i class="bi bi-info-circle text-danger me-1"></i> This record will be permanently purged from the database. This action cannot be undone.
                </div>
            </div>
            <div class="modal-footer bg-light py-3 px-4">
                <button type="button" class="btn btn-jm-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger fw-semibold px-4" id="confirmDeleteButton">
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
