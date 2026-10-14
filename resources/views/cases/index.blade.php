@extends('layouts.app')

@section('title', 'Medical Implant Cases')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col-md-7">
        <h2 class="fw-bold mb-1">Medical Implant Cases</h2>
        <p class="text-muted mb-0">Search, filter, and track patient-specific implants across all clinical stages.</p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0">
        @can('create', App\Models\MedicalCase::class)
        <a href="{{ route('cases.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Log New Case
        </a>
        @endcan
    </div>
</div>

<!-- Search & Filter Card -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3 p-md-4">
        <form method="GET" action="{{ route('cases.index') }}" class="row g-3 align-items-end">
            <!-- Search by Case Number -->
            <div class="col-md-3">
                <label for="case_number" class="form-label small fw-semibold text-secondary">Case Number</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control border-start-0" id="case_number" name="case_number" 
                           value="{{ request('case_number') }}" placeholder="e.g. JM-100201">
                </div>
            </div>

            <!-- Search by Surgeon Name -->
            <div class="col-md-3">
                <label for="surgeon_name" class="form-label small fw-semibold text-secondary">Surgeon Name</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-person"></i></span>
                    <input type="text" class="form-control border-start-0" id="surgeon_name" name="surgeon_name" 
                           value="{{ request('surgeon_name') }}" placeholder="e.g. Dr. Chen">
                </div>
            </div>

            <!-- Filter by Status -->
            <div class="col-md-2">
                <label for="status" class="form-label small fw-semibold text-secondary">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter by Priority -->
            <div class="col-md-2">
                <label for="priority" class="form-label small fw-semibold text-secondary">Priority</label>
                <select class="form-select" id="priority" name="priority">
                    <option value="">All Priorities</option>
                    @foreach($priorities as $priority)
                        <option value="{{ $priority }}" {{ request('priority') === $priority ? 'selected' : '' }}>
                            {{ $priority }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Submit & Reset Action Buttons -->
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 fw-semibold">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                @if(request()->anyFilled(['case_number', 'surgeon_name', 'status', 'priority']))
                    <a href="{{ route('cases.index') }}" class="btn btn-outline-secondary" title="Reset Filters">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Cases Table Card -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <div>
            <span class="fw-bold text-dark">Clinical Records</span>
            <span class="badge bg-light text-secondary border ms-2">Showing {{ $cases->count() }} of {{ $cases->total() }}</span>
        </div>
        <div class="small text-muted">
            Page {{ $cases->currentPage() }} of {{ $cases->lastPage() }}
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">
                            <a href="{{ route('cases.index', array_merge(request()->query(), ['sort' => 'case_number', 'direction' => (request('sort') === 'case_number' && request('direction') === 'asc') ? 'desc' : 'asc'])) }}" class="text-dark text-decoration-none d-flex align-items-center gap-1">
                                Case #
                                @if(request('sort') === 'case_number')
                                    <i class="bi bi-arrow-{{ request('direction') === 'asc' ? 'up' : 'down' }} text-primary"></i>
                                @endif
                            </a>
                        </th>
                        <th>Patient Ref</th>
                        <th>
                            <a href="{{ route('cases.index', array_merge(request()->query(), ['sort' => 'surgeon_name', 'direction' => (request('sort') === 'surgeon_name' && request('direction') === 'asc') ? 'desc' : 'asc'])) }}" class="text-dark text-decoration-none d-flex align-items-center gap-1">
                                Surgeon Name
                                @if(request('sort') === 'surgeon_name')
                                    <i class="bi bi-arrow-{{ request('direction') === 'asc' ? 'up' : 'down' }} text-primary"></i>
                                @endif
                            </a>
                        </th>
                        <th>Implant Type</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>
                            <a href="{{ route('cases.index', array_merge(request()->query(), ['sort' => 'surgery_date', 'direction' => (request('sort') === 'surgery_date' && request('direction') === 'asc') ? 'desc' : 'asc'])) }}" class="text-dark text-decoration-none d-flex align-items-center gap-1">
                                Surgery Date
                                @if(request('sort') === 'surgery_date')
                                    <i class="bi bi-arrow-{{ request('direction') === 'asc' ? 'up' : 'down' }} text-primary"></i>
                                @endif
                            </a>
                        </th>
                        <th>Created By</th>
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cases as $case)
                    <tr>
                        <td class="ps-3 fw-bold text-primary font-monospace">
                            <a href="{{ route('cases.show', $case) }}" class="text-decoration-none">
                                {{ $case->case_number }}
                            </a>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark font-monospace border">{{ $case->patient_reference }}</span>
                        </td>
                        <td class="fw-medium">{{ $case->surgeon_name }}</td>
                        <td class="text-muted small text-truncate" style="max-width: 220px;" title="{{ $case->implant_type }}">
                            {{ $case->implant_type }}
                        </td>
                        <td>
                            <span class="badge rounded-pill badge-status-{{ Str::slug($case->status) }} px-2 py-1">
                                {{ $case->status }}
                            </span>
                        </td>
                        <td>
                            <span class="badge rounded-pill badge-priority-{{ Str::slug($case->priority) }} px-2 py-1">
                                {{ $case->priority }}
                            </span>
                        </td>
                        <td class="small">
                            @if($case->surgery_date)
                                <span><i class="bi bi-calendar3 me-1 text-muted"></i>{{ $case->surgery_date->format('M d, Y') }}</span>
                            @else
                                <span class="text-muted fst-italic">Not scheduled</span>
                            @endif
                        </td>
                        <td class="small text-muted">
                            {{ $case->creator?->name ?? 'System' }}
                        </td>
                        <td class="text-end pe-3">
                            <div class="btn-group btn-group-sm">
                                <!-- View Button (Visible to Admin, Viewer, Engineer) -->
                                <a href="{{ route('cases.show', $case) }}" class="btn btn-outline-secondary" title="View Details">
                                    <i class="bi bi-eye"></i> View
                                </a>

                                <!-- Edit Button (Admin & Engineer Only) -->
                                @can('update', $case)
                                <a href="{{ route('cases.edit', $case) }}" class="btn btn-outline-primary" title="Edit Case">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                @endcan

                                <!-- Delete Button (Admin Only) -->
                                @can('delete', $case)
                                <button type="button" class="btn btn-outline-danger" title="Delete Case" 
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
                            <div class="py-4">
                                <i class="bi bi-clipboard-x text-muted" style="font-size: 3rem;"></i>
                                <h5 class="fw-bold mt-3 text-secondary">No Medical Cases Found</h5>
                                <p class="text-muted small mb-3">No cases match your current search and filter parameters.</p>
                                <a href="{{ route('cases.index') }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i> Clear All Filters
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination Footer -->
    @if($cases->hasPages())
    <div class="card-footer bg-white py-3 d-flex justify-content-between align-items-center">
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
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold" id="deleteConfirmModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Confirm Case Deletion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p>Are you sure you want to permanently delete medical case <strong id="modal-case-number" class="text-danger font-monospace"></strong>?</p>
                <div class="alert alert-warning small mb-0">
                    <i class="bi bi-info-circle me-1"></i> This action cannot be undone. All clinical specifications and logs associated with this case will be removed.
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger fw-semibold" id="confirmDeleteButton">
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
