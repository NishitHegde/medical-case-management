@extends('layouts.app')

@section('title', 'Clinical Dashboard')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col-md-7">
        <h2 class="fw-bold mb-1">Clinical Case Dashboard</h2>
        <p class="text-muted mb-0">Overview of patient-specific medical implant cases and operational status.</p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0">
        <a href="{{ route('cases.index') }}" class="btn btn-outline-primary me-2">
            <i class="bi bi-list-ul me-1"></i> Browse All Cases
        </a>
        @can('create', App\Models\MedicalCase::class)
        <a href="{{ route('cases.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> New Case
        </a>
        @endcan
    </div>
</div>

<!-- Case Status Metric Cards -->
<div class="row g-3 mb-4">
    <!-- Total Cases -->
    <div class="col-sm-6 col-xl-2">
        <div class="card h-100 border-0 shadow-sm" style="border-left: 4px solid #0f2b48 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Total Cases</span>
                    <span class="badge bg-light text-dark p-2"><i class="bi bi-folder2 text-primary fs-6"></i></span>
                </div>
                <div class="h2 fw-bold mb-0 text-dark">{{ $counts['total'] }}</div>
                <div class="small text-muted mt-1">Across all phases</div>
            </div>
        </div>
    </div>

    <!-- Planning Cases -->
    <div class="col-sm-6 col-xl-2">
        <div class="card h-100 border-0 shadow-sm" style="border-left: 4px solid #3b82f6 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Planning</span>
                    <span class="badge bg-light text-dark p-2"><i class="bi bi-diagram-3 text-info fs-6"></i></span>
                </div>
                <div class="h2 fw-bold mb-0 text-primary">{{ $counts['planning'] }}</div>
                <div class="small text-muted mt-1">CAD & design phase</div>
            </div>
        </div>
    </div>

    <!-- In Review Cases -->
    <div class="col-sm-6 col-xl-2">
        <div class="card h-100 border-0 shadow-sm" style="border-left: 4px solid #f59e0b !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">In Review</span>
                    <span class="badge bg-light text-dark p-2"><i class="bi bi-hourglass-split text-warning fs-6"></i></span>
                </div>
                <div class="h2 fw-bold mb-0 text-warning">{{ $counts['in_review'] }}</div>
                <div class="small text-muted mt-1">Surgeon inspection</div>
            </div>
        </div>
    </div>

    <!-- Approved Cases -->
    <div class="col-sm-6 col-xl-2">
        <div class="card h-100 border-0 shadow-sm" style="border-left: 4px solid #0d9488 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Approved</span>
                    <span class="badge bg-light text-dark p-2"><i class="bi bi-check2-circle text-teal fs-6"></i></span>
                </div>
                <div class="h2 fw-bold mb-0 text-info">{{ $counts['approved'] }}</div>
                <div class="small text-muted mt-1">Ready for 3D printing</div>
            </div>
        </div>
    </div>

    <!-- Completed Cases -->
    <div class="col-sm-6 col-xl-2">
        <div class="card h-100 border-0 shadow-sm" style="border-left: 4px solid #10b981 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Completed</span>
                    <span class="badge bg-light text-dark p-2"><i class="bi bi-patch-check text-success fs-6"></i></span>
                </div>
                <div class="h2 fw-bold mb-0 text-success">{{ $counts['completed'] }}</div>
                <div class="small text-muted mt-1">Delivered & implanted</div>
            </div>
        </div>
    </div>

    <!-- Draft & Cancelled -->
    <div class="col-sm-6 col-xl-2">
        <div class="card h-100 border-0 shadow-sm" style="border-left: 4px solid #64748b !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Draft / Other</span>
                    <span class="badge bg-light text-dark p-2"><i class="bi bi-archive text-secondary fs-6"></i></span>
                </div>
                <div class="h2 fw-bold mb-0 text-secondary">{{ $counts['draft'] + $counts['cancelled'] }}</div>
                <div class="small text-muted mt-1">Draft: {{ $counts['draft'] }} | Cancel: {{ $counts['cancelled'] }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Cases Table -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i> Recently Logged Implant Cases</h5>
                <a href="{{ route('cases.index') }}" class="small fw-semibold text-decoration-none">View All Cases &rarr;</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Case #</th>
                                <th>Patient Ref</th>
                                <th>Surgeon</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th class="text-end pe-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentCases as $case)
                            <tr>
                                <td class="ps-3 fw-bold text-primary">
                                    <a href="{{ route('cases.show', $case) }}" class="text-decoration-none">{{ $case->case_number }}</a>
                                </td>
                                <td><span class="font-monospace small">{{ $case->patient_reference }}</span></td>
                                <td>{{ $case->surgeon_name }}</td>
                                <td>
                                    <span class="badge rounded-pill badge-status-{{ Str::slug($case->status) }}">
                                        {{ $case->status }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill badge-priority-{{ Str::slug($case->priority) }}">
                                        {{ $case->priority }}
                                    </span>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('cases.show', $case) }}" class="btn btn-sm btn-outline-secondary py-1 px-2">
                                        <i class="bi bi-eye"></i> Details
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    No medical cases found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Info / Public Holidays Side Column -->
    <div class="col-lg-4">
        <!-- System Role & Permissions Summary -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-shield-lock me-2 text-primary"></i> Current User Role & Permissions</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="fs-1 text-primary me-3">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-5">{{ Auth::user()->name }}</div>
                        <span class="role-badge badge-role-{{ Auth::user()->role }}">{{ strtoupper(Auth::user()->role) }}</span>
                    </div>
                </div>
                <div class="small">
                    @if(Auth::user()->isAdmin())
                        <div class="text-success fw-semibold"><i class="bi bi-check-circle-fill me-1"></i> Full Administrative Access</div>
                        <div class="text-muted">You can create, edit, change status, and delete all medical cases.</div>
                    @elseif(Auth::user()->isEngineer())
                        <div class="text-warning fw-semibold"><i class="bi bi-check-circle-fill me-1"></i> Biomedical Engineering Access</div>
                        <div class="text-muted">You can create, edit, and update case statuses. Deletion is restricted to Admins.</div>
                    @else
                        <div class="text-primary fw-semibold"><i class="bi bi-eye-fill me-1"></i> Read-Only Clinical Viewer</div>
                        <div class="text-muted">You can search, filter, and inspect case records. Creation, editing, and deletion are restricted.</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Public Holidays Widget (Bonus) -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="bi bi-calendar-event me-2 text-info"></i> Upcoming Public Holidays</h6>
                <span class="badge bg-light text-secondary border">API Sync</span>
            </div>
            <div class="card-body p-3">
                <p class="small text-muted mb-3">Live holiday schedule integrated via external Public Holiday API to ensure surgery dates do not conflict with facility closures.</p>
                @if(count($upcomingHolidays) > 0)
                    <ul class="list-group list-group-flush small">
                        @foreach($upcomingHolidays as $holiday)
                            <li class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold">{{ $holiday['name'] }}</div>
                                    <div class="text-muted small">{{ $holiday['localName'] ?? '' }}</div>
                                </div>
                                <span class="badge bg-light text-primary border">{{ $holiday['date'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="alert alert-light border small text-muted mb-0">
                        <i class="bi bi-info-circle me-1"></i> Public Holiday API currently offline or cached. Fallback scheduling active.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
