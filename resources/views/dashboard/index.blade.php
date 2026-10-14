@extends('layouts.app')

@section('title', 'Clinical Dashboard')

@section('content')
<!-- Header Greeting & Actions -->
<div class="row align-items-center mb-4 g-3">
    <div class="col-md-7">
        <div class="d-flex align-items-center gap-2 mb-1">
            <h2 class="fw-bold mb-0 text-dark tracking-tight">Clinical Operations Dashboard</h2>
            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill small fw-semibold px-2 py-1">Live Telemetry</span>
        </div>
        <p class="text-muted mb-0 small">
            Real-time tracking of patient-specific cranial, mandibular, orthopaedic, and spinal implant lifecycles.
        </p>
    </div>
    <div class="col-md-5 text-md-end d-flex justify-content-md-end gap-2">
        <a href="{{ route('cases.index') }}" class="btn btn-jm-secondary d-inline-flex align-items-center gap-2">
            <i class="bi bi-search"></i>
            <span>Browse Cases</span>
        </a>
        @can('create', App\Models\MedicalCase::class)
        <a href="{{ route('cases.create') }}" class="btn btn-jm-primary d-inline-flex align-items-center gap-2">
            <i class="bi bi-plus-lg"></i>
            <span>Log New Case</span>
        </a>
        @endcan
    </div>
</div>

<!-- Primary Metric Cards -->
<div class="row g-3 mb-4">
    <!-- Total Active Cases -->
    <div class="col-xl-3 col-sm-6">
        <div class="card-stat">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="text-muted small fw-bold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Total Managed Cases</div>
                    <div class="h2 fw-bold text-dark mt-2 mb-1 tracking-tight">{{ $counts['total'] }}</div>
                    <div class="small text-muted d-flex align-items-center gap-1">
                        <i class="bi bi-layers-fill text-primary"></i> Across all clinical stages
                    </div>
                </div>
                <div class="card-stat-icon" style="background: #eff6ff; color: #2563eb;">
                    <i class="bi bi-folder2-open"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Planning Cases -->
    <div class="col-xl-3 col-sm-6">
        <div class="card-stat">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="text-muted small fw-bold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">In CAD &amp; Planning</div>
                    <div class="h2 fw-bold text-primary mt-2 mb-1 tracking-tight">{{ $counts['planning'] }}</div>
                    <div class="small text-muted d-flex align-items-center gap-1">
                        <i class="bi bi-diagram-3-fill text-info"></i> Anatomical sizing &amp; design
                    </div>
                </div>
                <div class="card-stat-icon" style="background: #eff6ff; color: #3b82f6;">
                    <i class="bi bi-diagram-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- In Review Cases -->
    <div class="col-xl-3 col-sm-6">
        <div class="card-stat">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="text-muted small fw-bold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Surgeon Review</div>
                    <div class="h2 fw-bold mt-2 mb-1 tracking-tight" style="color: #d97706;">{{ $counts['in_review'] }}</div>
                    <div class="small text-muted d-flex align-items-center gap-1">
                        <i class="bi bi-hourglass-split text-warning"></i> Pending clinician sign-off
                    </div>
                </div>
                <div class="card-stat-icon" style="background: #fffbeb; color: #d97706;">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Approved & Ready for 3D Printing -->
    <div class="col-xl-3 col-sm-6">
        <div class="card-stat">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="text-muted small fw-bold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Approved for Production</div>
                    <div class="h2 fw-bold mt-2 mb-1 tracking-tight" style="color: #0d9488;">{{ $counts['approved'] }}</div>
                    <div class="small text-muted d-flex align-items-center gap-1">
                        <i class="bi bi-patch-check-fill text-teal" style="color: #0d9488;"></i> Released to 3D printing
                    </div>
                </div>
                <div class="card-stat-icon" style="background: #f0fdfa; color: #0d9488;">
                    <i class="bi bi-check2-circle"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Workflow Pipeline Distribution Bar -->
<div class="card card-clean mb-4 p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="fw-bold text-dark small text-uppercase tracking-wider" style="font-size: 0.75rem;">
            <i class="bi bi-bar-chart-steps me-1 text-primary"></i> Clinical Lifecycle Progress Pipeline
        </div>
        <div class="small text-muted">
            <span class="me-3"><span class="badge rounded-circle bg-secondary p-1 me-1"></span>Draft: {{ $counts['draft'] }}</span>
            <span class="me-3"><span class="badge rounded-circle bg-primary p-1 me-1"></span>Planning: {{ $counts['planning'] }}</span>
            <span class="me-3"><span class="badge rounded-circle bg-warning p-1 me-1"></span>Review: {{ $counts['in_review'] }}</span>
            <span class="me-3"><span class="badge rounded-circle p-1 me-1" style="background:#0d9488;"></span>Approved: {{ $counts['approved'] }}</span>
            <span><span class="badge rounded-circle bg-success p-1 me-1"></span>Completed: {{ $counts['completed'] }}</span>
        </div>
    </div>

    @php
        $totalNonZero = max($counts['total'], 1);
        $pctDraft = round(($counts['draft'] / $totalNonZero) * 100);
        $pctPlanning = round(($counts['planning'] / $totalNonZero) * 100);
        $pctReview = round(($counts['in_review'] / $totalNonZero) * 100);
        $pctApproved = round(($counts['approved'] / $totalNonZero) * 100);
        $pctCompleted = round(($counts['completed'] / $totalNonZero) * 100);
    @endphp

    <div class="progress" style="height: 12px; border-radius: 20px; background-color: #f1f5f9;">
        <div class="progress-bar bg-secondary" role="progressbar" style="width: {{ $pctDraft }}%" title="Draft ({{ $counts['draft'] }})"></div>
        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $pctPlanning }}%" title="Planning ({{ $counts['planning'] }})"></div>
        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $pctReview }}%" title="In Review ({{ $counts['in_review'] }})"></div>
        <div class="progress-bar" role="progressbar" style="width: {{ $pctApproved }}%; background-color: #0d9488;" title="Approved ({{ $counts['approved'] }})"></div>
        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $pctCompleted }}%" title="Completed ({{ $counts['completed'] }})"></div>
    </div>
</div>

<!-- Main Dashboard Grid -->
<div class="row g-4">
    <!-- Left Column: Recently Logged Cases -->
    <div class="col-lg-8">
        <div class="card card-clean h-100">
            <div class="card-header bg-transparent border-0 p-4 pb-0 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1 text-dark">Recently Active Implant Cases</h5>
                    <p class="text-muted small mb-0">Latest surgical cases created and transitioned through CAD design.</p>
                </div>
                <a href="{{ route('cases.index') }}" class="btn btn-sm btn-jm-secondary">
                    View All &rarr;
                </a>
            </div>

            <div class="card-body p-0 pt-3">
                <div class="table-responsive">
                    <table class="table table-custom align-middle">
                        <thead>
                            <tr>
                                <th class="ps-4">Case #</th>
                                <th>Patient Ref</th>
                                <th>Surgeon</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentCases as $case)
                            <tr>
                                <td class="ps-4">
                                    <a href="{{ route('cases.show', $case) }}" class="fw-bold text-primary font-mono text-decoration-none">
                                        {{ $case->case_number }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary font-mono border">{{ $case->patient_reference }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="user-avatar-circle" style="width: 24px; height: 24px; font-size: 0.68rem; background: #94a3b8;">
                                            {{ strtoupper(substr($case->surgeon_name, 3, 1)) }}
                                        </div>
                                        <span class="fw-medium text-dark">{{ $case->surgeon_name }}</span>
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
                                <td class="text-end pe-4">
                                    <a href="{{ route('cases.show', $case) }}" class="btn btn-sm btn-light border py-1 px-2 rounded-2" title="Inspect Case">
                                        <i class="bi bi-eye"></i> Details
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    No recent cases logged yet.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: User Role & Public Holiday Calendar Integration -->
    <div class="col-lg-4">
        <!-- Role & Workstation Access Info -->
        <div class="card card-clean mb-4 p-4">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="user-avatar-circle" style="width: 46px; height: 46px; font-size: 1.15rem;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <div class="fw-bold text-dark fs-6">{{ Auth::user()->name }}</div>
                    <div class="d-flex align-items-center gap-2 mt-1">
                        <span class="role-pill role-pill-{{ Auth::user()->role }}">
                            {{ strtoupper(Auth::user()->role) }}
                        </span>
                        <span class="text-muted small font-mono">{{ Auth::user()->email }}</span>
                    </div>
                </div>
            </div>

            <div class="p-3 bg-light rounded-3 border small">
                <div class="fw-bold text-dark mb-1">
                    <i class="bi bi-shield-check text-success me-1"></i> Role Permissions
                </div>
                @if(Auth::user()->isAdmin())
                    <p class="text-muted mb-0" style="font-size: 0.82rem;">
                        <strong>Administrator Privileges:</strong> Full authorization to create cases, modify clinical specs, advance status, and delete records across both Web and REST API interfaces.
                    </p>
                @elseif(Auth::user()->isEngineer())
                    <p class="text-muted mb-0" style="font-size: 0.82rem;">
                        <strong>Biomedical Engineer Privileges:</strong> Authorized to create new cases and update design specifications. Deletion of records is restricted to Admins.
                    </p>
                @else
                    <p class="text-muted mb-0" style="font-size: 0.82rem;">
                        <strong>Clinical Viewer Privileges:</strong> Read-only access to browse, search, and inspect medical cases. Creation, modification, and deletion are restricted.
                    </p>
                @endif
            </div>
        </div>

        <!-- Public Holiday Surgical Scheduling Card (Bonus Integration) -->
        <div class="card card-clean p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-calendar-event-fill text-primary"></i> Surgical Facility Calendar
                    </h6>
                    <span class="text-muted small" style="font-size: 0.75rem;">External Public Holiday API Sync</span>
                </div>
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1" style="font-size: 0.65rem;">
                    <i class="bi bi-check2"></i> Live Feed
                </span>
            </div>

            <p class="text-muted small mb-3" style="font-size: 0.82rem; line-height: 1.5;">
                Surgeries scheduled on public holidays trigger clinical conflict warnings in the case details sheet to confirm hospital operating theater staffing.
            </p>

            @if(count($upcomingHolidays) > 0)
                <div class="list-group list-group-flush border-0">
                    @foreach($upcomingHolidays as $holiday)
                        <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center border-0 border-bottom">
                            <div>
                                <div class="fw-semibold text-dark small">{{ $holiday['name'] }}</div>
                                <div class="text-muted" style="font-size: 0.72rem;">{{ $holiday['localName'] ?? 'Public Holiday' }}</div>
                            </div>
                            <span class="badge bg-light text-primary border font-mono small">
                                {{ $holiday['date'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-3 bg-light rounded-3 text-muted small text-center">
                    <i class="bi bi-cloud-check text-primary fs-5 mb-1 d-block"></i>
                    No upcoming facility holiday closures detected.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
