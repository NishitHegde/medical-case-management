<div class="row g-3">
    <!-- Case Number -->
    <div class="col-md-6">
        <label for="case_number" class="form-label fw-semibold text-secondary">
            Case Number <span class="text-danger">*</span>
        </label>
        <div class="input-group">
            <span class="input-group-text bg-light text-muted"><i class="bi bi-hash"></i></span>
            <input type="text" class="form-control @error('case_number') is-invalid @enderror" 
                   id="case_number" name="case_number" 
                   value="{{ old('case_number', $case->case_number ?? '') }}" 
                   placeholder="e.g. JM-100250" required>
        </div>
        <div class="form-text small text-muted">Unique clinical identifier (e.g. JM-XXXXXX).</div>
        @error('case_number')
            <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
        @enderror
    </div>

    <!-- Patient Reference -->
    <div class="col-md-6">
        <label for="patient_reference" class="form-label fw-semibold text-secondary">
            Patient Reference Code <span class="text-danger">*</span>
        </label>
        <div class="input-group">
            <span class="input-group-text bg-light text-muted"><i class="bi bi-person-badge"></i></span>
            <input type="text" class="form-control @error('patient_reference') is-invalid @enderror" 
                   id="patient_reference" name="patient_reference" 
                   value="{{ old('patient_reference', $case->patient_reference ?? '') }}" 
                   placeholder="e.g. DUMMY-CR-1042" required>
        </div>
        <div class="form-text small text-muted">De-identified clinical reference. Never input real patient names.</div>
        @error('patient_reference')
            <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
        @enderror
    </div>

    <!-- Surgeon Name -->
    <div class="col-md-6">
        <label for="surgeon_name" class="form-label fw-semibold text-secondary">
            Surgeon Name <span class="text-danger">*</span>
        </label>
        <div class="input-group">
            <span class="input-group-text bg-light text-muted"><i class="bi bi-person"></i></span>
            <input type="text" class="form-control @error('surgeon_name') is-invalid @enderror" 
                   id="surgeon_name" name="surgeon_name" 
                   value="{{ old('surgeon_name', $case->surgeon_name ?? '') }}" 
                   placeholder="e.g. Dr. Robert Chen, MD" required>
        </div>
        @error('surgeon_name')
            <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
        @enderror
    </div>

    <!-- Implant Type -->
    <div class="col-md-6">
        <label for="implant_type" class="form-label fw-semibold text-secondary">
            Implant Type <span class="text-danger">*</span>
        </label>
        <div class="input-group">
            <span class="input-group-text bg-light text-muted"><i class="bi bi-gear-wide-connected"></i></span>
            <input type="text" class="form-control @error('implant_type') is-invalid @enderror" 
                   id="implant_type" name="implant_type" 
                   value="{{ old('implant_type', $case->implant_type ?? '') }}" 
                   placeholder="e.g. Custom PEEK Cranial Implant" required>
        </div>
        @error('implant_type')
            <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
        @enderror
    </div>

    <!-- Case Status -->
    <div class="col-md-4">
        <label for="status" class="form-label fw-semibold text-secondary">
            Workflow Status <span class="text-danger">*</span>
        </label>
        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
            <option value="" disabled {{ old('status', $case->status ?? '') == '' ? 'selected' : '' }}>Select status</option>
            @foreach($statuses as $statusOption)
                <option value="{{ $statusOption }}" {{ old('status', $case->status ?? 'Draft') === $statusOption ? 'selected' : '' }}>
                    {{ $statusOption }}
                </option>
            @endforeach
        </select>
        @error('status')
            <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
        @enderror
    </div>

    <!-- Case Priority -->
    <div class="col-md-4">
        <label for="priority" class="form-label fw-semibold text-secondary">
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
            <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
        @enderror
    </div>

    <!-- Surgery Date -->
    <div class="col-md-4">
        <label for="surgery_date" class="form-label fw-semibold text-secondary">
            Scheduled Surgery Date
        </label>
        <div class="input-group">
            <span class="input-group-text bg-light text-muted"><i class="bi bi-calendar-date"></i></span>
            <input type="date" class="form-control @error('surgery_date') is-invalid @enderror" 
                   id="surgery_date" name="surgery_date" 
                   value="{{ old('surgery_date', $case->surgery_date ? $case->surgery_date->format('Y-m-d') : '') }}">
        </div>
        <div class="form-text small text-muted">Optional estimated or confirmed surgery date.</div>
        @error('surgery_date')
            <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="border-top mt-4 pt-3 d-flex justify-content-between align-items-center">
    <a href="{{ isset($case->id) ? route('cases.show', $case) : route('cases.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Cancel
    </a>
    <button type="submit" class="btn btn-primary px-4 fw-semibold">
        <i class="bi bi-check2-circle me-1"></i> {{ isset($case->id) ? 'Update Case' : 'Save Medical Case' }}
    </button>
</div>
