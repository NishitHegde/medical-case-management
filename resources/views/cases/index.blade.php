@extends('layouts.app')

@section('title', 'Medical Implant Cases')

@section('content')
<!-- Header Bar -->
<div class="row align-items-center mb-4 g-3">
    <div class="col-md-7">
        <div class="d-flex align-items-center gap-2 mb-1">
            <h2 class="fw-bold mb-0 text-dark tracking-tight">Medical Implant Cases</h2>
            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill small fw-semibold px-2 py-1">
                {{ $cases->total() }} Total Records
            </span>
        </div>
        <p class="text-muted mb-0 small">
            Browse, search, and manage patient-specific implants across planning, surgeon review, and manufacturing.
        </p>
    </div>
    <div class="col-md-5 text-md-end">
        @can('create', App\Models\MedicalCase::class)
        <a href="{{ route('cases.create') }}" class="btn btn-jm-primary d-inline-flex align-items-center gap-2">
            <i class="bi bi-plus-lg"></i>
            <span>Log New Medical Case</span>
        </a>
        @endcan
    </div>
</div>

<!-- Search & Filtering Card -->
<div class="card card-clean mb-4 p-3 p-md-4">
    <form method="GET" action="{{ route('cases.index') }}" class="row g-3 align-items-end">
        <!-- Search Case Number -->
        <div class="col-lg-3 col-md-6">
            <label for="case_number" class="form-label small fw-bold text-secondary text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Case Number</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-hash"></i></span>
                <input type="text" class="form-control border-start-0" id="case_number" name="case_number" 
                       value="{{ request('case_number') }}" placeholder="e.g. JM-100201">
            </div>
        </div>

        <!-- Search Surgeon Name -->
        <div class="col-lg-3 col-md-6">
            <label for="surgeon_name" class="form-label small fw-bold text-secondary text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Attending Surgeon</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                <input type="text" class="form-control border-start-0" id="surgeon_name" name="surgeon_name" 
                       value="{{ request('surgeon_name') }}" placeholder="e.g. Dr. Chen">
            </div>
        </div>

        <!-- Status Filter -->
        <div class="col-lg-2 col-md-4">
            <label for="status" class="form-label small fw-bold text-secondary text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Status</label>
            <select class="form-select" id="status" name="status">
                <option value="">All Statuses</option>
                @foreach($statuses as $status)
                    <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                        {{ $status }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Priority Filter -->
        <div class="col-lg-2 col-md-4">
            <label for="priority" class="form-label small fw-bold text-secondary text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Priority</label>
            <select class="form-select" id="priority" name="priority">
                <option value="">All Priorities</option>
                @foreach($priorities as $priority)
                    <option value="{{ $priority }}" {{ request('priority') === $priority ? 'selected' : '' }}>
                        {{ $priority }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Action Buttons -->
        <div class="col-lg-2 col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-jm-primary w-100 d-inline-flex justify-content-center align-items-center gap-1">
                <i class="bi bi-funnel"></i> Filter
            </button>
            @if(request()->anyFilled(['case_number', 'surgeon_name', 'status', 'priority']))
                <a href="{{ route('cases.index') }}" class="btn btn-jm-secondary" title="Reset Filters">
                    <i class="bi bi-x-lg"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Cases Table Card -->
<div class="card card-clean overflow-hidden">
    <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <span class="fw-bold text-dark fs-6">Clinical Records</span>
            <span class="badge bg-light text-secondary border">
                Showing {{ $cases->count() }} of {{ $cases->total() }}
            </span>
        </div>
        <div class="small text-muted font-mono">
            Page {{ $cases->currentPage() }} of {{ $cases->lastPage() }}
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
                <tr>
                    <th class="ps-4">
                        <a href="{{ route('cases.index', array_merge(request()->query(), ['sort' => 'case_number', 'direction' => (request('sort') === 'case_number' && request('direction') === 'asc') ? 'desc' : 'asc'])) }}" class="text-secondary text-decoration-none d-inline-flex align-items-center gap-1">
                            Case #
                            <i class="bi bi-arrow-{{ (request('sort') === 'case_number' && request('direction') === 'asc') ? 'up-circle-fill text-primary' : 'down-up text-muted' }}"></i>
                        </a>
                    </th>
                    <th>Patient Ref</th>
                    <th>
                        <a href="{{ route('cases.index', array_merge(request()->query(), ['sort' => 'surgeon_name', 'direction' => (request('sort') === 'surgeon_name' && request('direction') === 'asc') ? 'desc' : 'asc'])) }}" class="text-secondary text-decoration-none d-inline-flex align-items-center gap-1">
                            Surgeon Name
                            <i class="bi bi-arrow-{{ (request('sort') === 'surgeon_name' && request('direction') === 'asc') ? 'up-circle-fill text-primary' : 'down-up text-muted' }}"></i>
                        </a>
                    </th>
                    <th>Implant Specification</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>
                        <a href="{{ route('cases.index', array_merge(request()->query(), ['sort' => 'surgery_date', 'direction' => (request('sort') === 'surgery_date' && request('direction') === 'asc') ? 'desc' : 'asc'])) }}" class="text-secondary text-decoration-none d-inline-flex align-items-center gap-1">
                            Surgery Date
                            <i class="bi bi-arrow-{{ (request('sort') === 'surgery_date' && request('direction') === 'asc') ? 'up-circle-fill text-primary' : 'down-up text-muted' }}"></i>
                        </a>
                    </th>
                    <th>Created By</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cases as $case)
                <tr>
                    <td class="ps-4">
                        <a href="{{ route('cases.show', $case) }}" class="fw-bold text-primary font-mono text-decoration-none">
                            {{ $case->case_number }}
                        </a>
                    </td>
                    <td>
                        <span class="badge bg-light text-secondary font-mono border">
                            {{ $case->patient_reference }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="user-avatar-circle" style="width: 26px; height: 26px; font-size: 0.7rem; background: #64748b;">
                                {{ strtoupper(substr($case->surgeon_name, 3, 1)) }}
                            </div>
                            <span class="fw-semibold text-dark">{{ $case->surgeon_name }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="text-dark small fw-medium text-truncate" style="max-width: 220px;" title="{{ $case->implant_type }}">
                            {{ $case->implant_type }}
                        </div>
                    </td>
                    <td>
                        <span class="status-pill status-{{ Str::slug($case->status) }}">
                            <span class="dot"></span>
                            {{ $case->status }}
                        </span>
                    </td>
                    <td>
                        <span class="priority-badge priority-{{ Str::slug($case->priority) }}">
                            {{ $case->priority }}
                        </span>
                    </td>
                    <td class="small">
                        @if($case->surgery_date)
                            <span class="d-inline-flex align-items-center gap-1 text-dark">
                                <i class="bi bi-calendar3 text-muted"></i>
                                {{ $case->surgery_date->format('M d, Y') }}
                            </span>
                        @else
                            <span class="text-muted fst-italic">Not scheduled</span>
                        @endif
                    </td>
                    <td class="small text-muted">
                        {{ $case->creator?->name ?? 'System' }}
                    </td>
                    <td class="text-end pe-4">
                        <div class="btn-group btn-group-sm">
                            <!-- View Button (Admin, Viewer, Engineer) -->
                            <a href="{{ route('cases.show', $case) }}" class="btn btn-light border" title="Inspect Case">
                                <i class="bi bi-eye text-secondary"></i>
                            </a>

                            <!-- Edit Button (Admin & Engineer Only) -->
                            @can('update', $case)
                            <a href="{{ route('cases.edit', $case) }}" class="btn btn-light border" title="Edit Case">
                                <i class="bi bi-pencil text-primary"></i>
                            </a>
                            @endcan

                            <!-- Delete Button (Admin Only) -->
                            @can('delete', $case)
                            <button type="button" class="btn btn-light border text-danger" title="Delete Case" 
                                    onclick="confirmDelete('{{ $case->id }}', '{{ $case->case_number }}')">
                                <i class="bi bi-trash"></i>
                            </button>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-5">
                        <div class="py-5">
                            <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle p-4 mb-3 text-muted">
                                <i class="bi bi-clipboard-x fs-1"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-1">No Matching Medical Cases</h5>
                            <p class="text-muted small mb-3">No cases found with the current filters. Try searching for a different keyword or resetting filters.</p>
                            <a href="{{ route('cases.index') }}" class="btn btn-sm btn-jm-secondary">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset All Filters
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Footer -->
    @if($cases->hasPages())
    <div class="card-footer bg-white py-3 px-4 border-top d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div class="small text-muted">
            Showing {{ $cases->firstItem() ?? 0 }} to {{ $cases->lastItem() ?? 0 }} of {{ $cases->total() }} cases
        </div>
        <div>
            {{ $cases->links('pagination::bootstrap-5') }}
        </div>
    </div>
    @endif
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
                    <i class="bi bi-info-circle text-danger me-1"></i> This action permanently purges the clinical record from the database. It cannot be recovered.
                </div>
            </div>
            <div class="modal-footer bg-light py-3 px-4">
                <button type="button" class="btn btn-jm-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger fw-semibold px-4" id="confirmDeleteButton">
                    <i class="bi bi-trash me-1"></i> Delete Case
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
