@extends('layouts.app')

@section('title', 'Promoter Share Sell Process - Promoter Share Management')
@section('page-title', 'Promoter Share Sell Process Initiate')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">Promoter Share Sell Application</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('sell-applications.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="seller_id" class="form-label">Name of Share Holder (Promoter) <span class="text-danger">*</span></label>
                        <select class="form-select @error('seller_id') is-invalid @enderror" id="seller_id" name="seller_id" required>
                            <option value="">Select Promoter Share Holder</option>
                            @foreach($shareholders as $shareholder)
                                <option value="{{ $shareholder->id }}"
                                        data-type="{{ $shareholder->type }}"
                                        data-shares="{{ $shareholder->share_quantity }}"
                                        data-boid="{{ $shareholder->boid }}"
                                        data-demat="{{ $shareholder->demat_account }}"
                                        {{ old('seller_id', request('seller_id')) == $shareholder->id ? 'selected' : '' }}>
                                    {{ $shareholder->name }}
                                    ({{ ucfirst($shareholder->type) }} - {{ number_format($shareholder->share_quantity) }} shares)
                                </option>
                            @endforeach
                        </select>
                        @error('seller_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Auto-filled Seller Type -->
                    <div class="mb-3">
                        <label for="seller_type" class="form-label">Seller Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('seller_type') is-invalid @enderror" id="seller_type" name="seller_type" required readonly>
                            <option value="">Select seller first</option>
                            <option value="individual">Individual</option>
                            <option value="institutional">Institutional</option>
                        </select>
                        @error('seller_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="share_quantity_to_sell" class="form-label">Share Quantity to Sell <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('share_quantity_to_sell') is-invalid @enderror"
                                   id="share_quantity_to_sell" name="share_quantity_to_sell" value="{{ old('share_quantity_to_sell') }}" min="1" required>
                            <small class="form-text text-muted" id="available-shares"></small>
                            @error('share_quantity_to_sell')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="application_date" class="form-label">Application Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('application_date') is-invalid @enderror"
                                   id="application_date" name="application_date" value="{{ old('application_date', date('Y-m-d')) }}" required>
                            @error('application_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="proposed_price_per_share" class="form-label">Proposed Price per Share</label>
                        <input type="number" class="form-control @error('proposed_price_per_share') is-invalid @enderror"
                               id="proposed_price_per_share" name="proposed_price_per_share" value="{{ old('proposed_price_per_share') }}"
                               step="0.01" min="0">
                        @error('proposed_price_per_share')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="boid" class="form-label">BOID (Auto-filled)</label>
                            <input type="text" class="form-control" id="boid" name="boid" value="{{ old('boid') }}" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="demat_account" class="form-label">Demat Account Details</label>
                            <input type="text" class="form-control @error('demat_account') is-invalid @enderror"
                                   id="demat_account" name="demat_account" value="{{ old('demat_account') }}">
                            @error('demat_account')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason for Selling</label>
                        <textarea class="form-control @error('reason') is-invalid @enderror"
                                  id="reason" name="reason" rows="3">{{ old('reason') }}</textarea>
                        @error('reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Document Upload Section -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6 class="mb-0">Required Documents Upload</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="sell_application_doc" class="form-label">Application Form <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('sell_application_doc') is-invalid @enderror"
                                           id="sell_application_doc" name="sell_application_doc" accept=".pdf,.jpg,.jpeg,.png" required>
                                    <small class="form-text text-muted">PDF, JPG, PNG (Max: 5MB)</small>
                                    @error('sell_application_doc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="seller_citizenship" class="form-label">Citizenship of Applicant <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('seller_citizenship') is-invalid @enderror"
                                           id="seller_citizenship" name="seller_citizenship" accept=".pdf,.jpg,.jpeg,.png" required>
                                    <small class="form-text text-muted">PDF, JPG, PNG (Max: 5MB)</small>
                                    @error('seller_citizenship')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="seller_tax_clearance" class="form-label">Tax Clearance <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('seller_tax_clearance') is-invalid @enderror"
                                           id="seller_tax_clearance" name="seller_tax_clearance" accept=".pdf,.jpg,.jpeg,.png" required>
                                    <small class="form-text text-muted">PDF, JPG, PNG (Max: 5MB)</small>
                                    @error('seller_tax_clearance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="seller_cia_report" class="form-label">CIA Report <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('seller_cia_report') is-invalid @enderror"
                                           id="seller_cia_report" name="seller_cia_report" accept=".pdf,.jpg,.jpeg,.png" required>
                                    <small class="form-text text-muted">PDF, JPG, PNG (Max: 5MB)</small>
                                    @error('seller_cia_report')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Institutional Documents (Hidden by default) -->
                                <div class="col-md-6 mb-3" id="moa-aoa-upload" style="display: none;">
                                    <label for="seller_moa_aoa" class="form-label">MOA & AOA <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('seller_moa_aoa') is-invalid @enderror"
                                           id="seller_moa_aoa" name="seller_moa_aoa" accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="form-text text-muted">PDF, JPG, PNG (Max: 5MB)</small>
                                    @error('seller_moa_aoa')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3" id="decision-minute-upload" style="display: none;">
                                    <label for="seller_decision_minute" class="form-label">Decision Minute <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('seller_decision_minute') is-invalid @enderror"
                                           id="seller_decision_minute" name="seller_decision_minute" accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="form-text text-muted">PDF, JPG, PNG (Max: 5MB)</small>
                                    @error('seller_decision_minute')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="demat_account_details" class="form-label">Demat Account Details (Optional)</label>
                                    <input type="file" class="form-control @error('demat_account_details') is-invalid @enderror"
                                           id="demat_account_details" name="demat_account_details" accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="form-text text-muted">PDF, JPG, PNG (Max: 5MB)</small>
                                    @error('demat_account_details')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="seller_others" class="form-label">Others (Optional)</label>
                                    <input type="file" class="form-control @error('seller_others') is-invalid @enderror"
                                           id="seller_others" name="seller_others" accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="form-text text-muted">PDF, JPG, PNG (Max: 5MB)</small>
                                    @error('seller_others')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('sell-applications.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Submit Sell Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="mb-0">Required Documents for Sell Application</h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Application Form</span>
                        <span class="badge bg-danger">Required</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Citizenship of Applicant</span>
                        <span class="badge bg-danger">Required</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Tax Clearance</span>
                        <span class="badge bg-danger">Required</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>CIA Report</span>
                        <span class="badge bg-danger">Required</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center" id="moa-aoa-item" style="display: none;">
                        <span>MOA & AOA</span>
                        <span class="badge bg-warning">Institutional</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center" id="decision-minute-item" style="display: none;">
                        <span>Decision Minute</span>
                        <span class="badge bg-warning">Institutional</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Demat Account Details</span>
                        <span class="badge bg-info">Optional</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Others</span>
                        <span class="badge bg-info">Optional</span>
                    </div>
                </div>

                <div class="mt-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        All required documents must be uploaded during application submission.
                    </small>
                </div>
            </div>
        </div>

        <div class="card shadow mt-3">
            <div class="card-header">
                <h6 class="mb-0">Process Flow</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6>1. Application Submission</h6>
                            <small>Submit sell application with required documents</small>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <h6>2. Board Decision</h6>
                            <small>Board of Directors review and decision</small>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <h6>3. Notice Publication</h6>
                            <small>Public notice in newspaper</small>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6>4. Buy Applications</h6>
                            <small>Interested buyers submit applications</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('seller_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const availableShares = document.getElementById('available-shares');
    const sellerType = document.getElementById('seller_type');
    const boidField = document.getElementById('boid');
    const dematField = document.getElementById('demat_account');
    const moaAoaItem = document.getElementById('moa-aoa-item');
    const decisionMinuteItem = document.getElementById('decision-minute-item');
    const moaAoaUpload = document.getElementById('moa-aoa-upload');
    const decisionMinuteUpload = document.getElementById('decision-minute-upload');
    const moaAoaInput = document.getElementById('seller_moa_aoa');
    const decisionMinuteInput = document.getElementById('seller_decision_minute');

    if (selectedOption.value) {
        const shares = selectedOption.dataset.shares;
        const type = selectedOption.dataset.type;
        const boid = selectedOption.dataset.boid;
        const demat = selectedOption.dataset.demat;

        availableShares.textContent = `Available shares: ${parseInt(shares).toLocaleString()}`;

        // Auto-fill seller type
        sellerType.value = type;

        // Auto-fill BOID and demat account
        boidField.value = boid || '';
        dematField.value = demat || '';

        // Show/hide institutional documents
        if (type === 'institutional') {
            moaAoaItem.style.display = 'block';
            decisionMinuteItem.style.display = 'block';
            moaAoaUpload.style.display = 'block';
            decisionMinuteUpload.style.display = 'block';
            moaAoaInput.required = true;
            decisionMinuteInput.required = true;
        } else {
            moaAoaItem.style.display = 'none';
            decisionMinuteItem.style.display = 'none';
            moaAoaUpload.style.display = 'none';
            decisionMinuteUpload.style.display = 'none';
            moaAoaInput.required = false;
            decisionMinuteInput.required = false;
        }
    } else {
        availableShares.textContent = '';
        sellerType.value = '';
        boidField.value = '';
        dematField.value = '';
        moaAoaItem.style.display = 'none';
        decisionMinuteItem.style.display = 'none';
        moaAoaUpload.style.display = 'none';
        decisionMinuteUpload.style.display = 'none';
        moaAoaInput.required = false;
        decisionMinuteInput.required = false;
    }
});

// Trigger change event if seller is pre-selected
if (document.getElementById('seller_id').value) {
    document.getElementById('seller_id').dispatchEvent(new Event('change'));
}
</script>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.timeline::before {
    content: '';
    position: absolute;
    left: -30px;
    top: 6px;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}
</style>
@endsection
