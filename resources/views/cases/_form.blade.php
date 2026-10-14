<div class="row g-4">
    <!-- Section 1: Clinical Identification -->
    <div class="col-12">
        <div class="d-flex align-items-center gap-2 pb-2 border-bottom">
            <span class="user-avatar-circle" style="width: 26px; height: 26px; font-size: 0.75rem; background: #2563eb;">1</span>
            <h6 class="fw-bold text-dark mb-0">Clinical Case Identification</h6>
        </div>
    </div>

    <!-- Case Number -->
    <div class="col-md-6">
        <label for="case_number" class="form-label small fw-bold text-secondary text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">
            Case Number <span class="text-danger">*</span>
        </label>
        <div class="input-group">
            <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-hash"></i></span>
            <input type="text" class="form-control border-start-0 font-mono @error('case_number') is-invalid @enderror" 
                   id="case_number" name="case_number" 
                   value="{{ old('case_number', $case->case_number ?? '') }}" 
                   placeholder="e.g. JM-100250" required>
        </div>
        <div class="form-text small text-muted">Unique tracking identifier across CAD design and manufacturing.</div>
        @error('case_number')
            <div class="text-danger small mt-1 d-flex align-items-center gap-1"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>
        @enderror
    </div>

    <!-- Patient Reference -->
    <div class="col-md-6">
        <label for="patient_reference" class="form-label small fw-bold text-secondary text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">
            Patient Reference Code <span class="text-danger">*</span>
        </label>
        <div class="input-group">
            <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-person-badge"></i></span>
            <input type="text" class="form-control border-start-0 font-mono @error('patient_reference') is-invalid @enderror" 
                   id="patient_reference" name="patient_reference" 
                   value="{{ old('patient_reference', $case->patient_reference ?? '') }}" 
                   placeholder="e.g. DUMMY-CR-1042" required>
        </div>
        <div class="form-text small text-muted">Synthetic de-identified reference code. Never input real patient names (HIPAA).</div>
        @error('patient_reference')
            <div class="text-danger small mt-1 d-flex align-items-center gap-1"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>
        @enderror
    </div>

    <!-- Section 2: Surgical Team & Anatomical Specification -->
    <div class="col-12 mt-4">
        <div class="d-flex align-items-center gap-2 pb-2 border-bottom">
            <span class="user-avatar-circle" style="width: 26px; height: 26px; font-size: 0.75rem; background: #0d9488;">2</span>
            <h6 class="fw-bold text-dark mb-0">Surgical Team &amp; Anatomical Specification</h6>
        </div>
    </div>

    <!-- Surgeon Name -->
    <div class="col-md-6">
        <label for="surgeon_name" class="form-label small fw-bold text-secondary text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">
            Attending Surgeon <span class="text-danger">*</span>
        </label>
        <div class="input-group">
            <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-person"></i></span>
            <input type="text" class="form-control border-start-0 @error('surgeon_name') is-invalid @enderror" 
                   id="surgeon_name" name="surgeon_name" 
                   value="{{ old('surgeon_name', $case->surgeon_name ?? '') }}" 
                   placeholder="e.g. Dr. Robert Chen, MD" required>
        </div>
        @error('surgeon_name')
            <div class="text-danger small mt-1 d-flex align-items-center gap-1"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>
        @enderror
    </div>

    <!-- Implant Type -->
    <div class="col-md-6">
        <label for="implant_type" class="form-label small fw-bold text-secondary text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">
            Implant Type / Material <span class="text-danger">*</span>
        </label>
        <div class="input-group">
            <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-gear-wide-connected"></i></span>
            <input type="text" class="form-control border-start-0 @error('implant_type') is-invalid @enderror" 
                   id="implant_type" name="implant_type" 
                   value="{{ old('implant_type', $case->implant_type ?? '') }}" 
                   placeholder="e.g. Custom PEEK Cranial Implant" required>
        </div>
        @error('implant_type')
            <div class="text-danger small mt-1 d-flex align-items-center gap-1"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>
        @enderror
    </div>

    <!-- Section 3: Workflow Status & Scheduling -->
    <div class="col-12 mt-4">
        <div class="d-flex align-items-center gap-2 pb-2 border-bottom">
            <span class="user-avatar-circle" style="width: 26px; height: 26px; font-size: 0.75rem; background: #f59e0b;">3</span>
            <h6 class="fw-bold text-dark mb-0">Workflow Stage &amp; Clinical Scheduling</h6>
        </div>
    </div>

    <!-- Status -->
    <div class="col-md-4">
        <label for="status" class="form-label small fw-bold text-secondary text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">
            Workflow Status <span class="text-danger">*</span>
        </label>
        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
            <option value="" disabled {{ old('status', $case->status ?? '') == '' ? 'selected' : '' }}>Select workflow status</option>
            @foreach($statuses as $statusOption)
                <option value="{{ $statusOption }}" {{ old('status', $case->status ?? 'Draft') === $statusOption ? 'selected' : '' }}>
                    {{ $statusOption }}
                </option>
            @endforeach
        </select>
        @error('status')
            <div class="text-danger small mt-1 d-flex align-items-center gap-1"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>
        @enderror
    </div>

    <!-- Priority -->
    <div class="col-md-4">
        <label for="priority" class="form-label small fw-bold text-secondary text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">
            Clinical Priority <span class="text-danger">*</span>
        </label>
        <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
            <option value="" disabled {{ old('priority', $case->priority ?? '') == '' ? 'selected' : '' }}>Select priority</option>
            @foreach($priorities as $priorityOption)
                <option value="{{ $priorityOption }}" {{ old('priority', $case->priority ?? 'Medium') === $priorityOption ? 'selected' : '' }}>
                    {{ $priorityOption }}
                </option>
            @endforeach
        </select>
        @error('priority')
            <div class="text-danger small mt-1 d-flex align-items-center gap-1"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>
        @enderror
    </div>

    <!-- Surgery Date -->
    <div class="col-md-4">
        <label for="surgery_date" class="form-label small fw-bold text-secondary text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">
            Surgery Date
        </label>
        <div class="input-group">
            <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-calendar-date"></i></span>
            <input type="date" class="form-control border-start-0 @error('surgery_date') is-invalid @enderror" 
                   id="surgery_date" name="surgery_date" 
                   value="{{ old('surgery_date', $case->surgery_date ? $case->surgery_date->format('Y-m-d') : '') }}">
        </div>
        <div class="form-text small text-muted">Planned date of implantation.</div>
        @error('surgery_date')
            <div class="text-danger small mt-1 d-flex align-items-center gap-1"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>
        @enderror
    </div>
</div>

<!-- Form Submission Controls -->
<div class="border-top mt-5 pt-4 d-flex justify-content-between align-items-center">
    <a href="{{ isset($case->id) ? route('cases.show', $case) : route('cases.index') }}" class="btn btn-jm-secondary">
        <i class="bi bi-arrow-left me-1"></i> Cancel &amp; Return
    </a>
    <button type="submit" class="btn btn-jm-primary px-4">
        <i class="bi bi-check2-circle me-1"></i> {{ isset($case->id) ? 'Save Case Changes' : 'Create Medical Case' }}
    </button>
</div>
