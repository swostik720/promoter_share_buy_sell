@extends('layouts.app')

@section('title', 'Buy Application - Promoter Share Management')
@section('page-title', 'Buyer Application for Promoter Shares')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">Buyer Application Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('buy-applications.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="sell_application_id" class="form-label">Available Promoter Shares for Sale <span class="text-danger">*</span></label>
                        <select class="form-select @error('sell_application_id') is-invalid @enderror" id="sell_application_id" name="sell_application_id" required>
                            <option value="">Select Available Shares</option>
                            @foreach($sellApplications as $sellApp)
                                <option value="{{ $sellApp->id }}" {{ old('sell_application_id') == $sellApp->id ? 'selected' : '' }}>
                                    {{ $sellApp->seller->name }} - {{ number_format($sellApp->share_quantity_to_sell) }} shares
                                    @if($sellApp->proposed_price_per_share)
                                        (Rs. {{ number_format($sellApp->proposed_price_per_share, 2) }} per share)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('sell_application_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Buyer Category Selection -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="buyer_category" class="form-label">Buyer Category <span class="text-danger">*</span></label>
                            <select class="form-select @error('buyer_category') is-invalid @enderror" id="buyer_category" name="buyer_category" required>
                                <option value="">Select Category</option>
                                <option value="existing_promoter" {{ old('buyer_category') == 'existing_promoter' ? 'selected' : '' }}>Existing Promoter</option>
                                <option value="public" {{ old('buyer_category') == 'public' ? 'selected' : '' }}>Public</option>
                            </select>
                            @error('buyer_category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="buyer_type" class="form-label">Buyer Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('buyer_type') is-invalid @enderror" id="buyer_type" name="buyer_type" required>
                                <option value="">Select Type</option>
                                <option value="individual" {{ old('buyer_type') == 'individual' ? 'selected' : '' }}>Individual</option>
                                <option value="institutional" {{ old('buyer_type') == 'institutional' ? 'selected' : '' }}>Institutional</option>
                            </select>
                            @error('buyer_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="buyer_name" class="form-label">Buyer Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('buyer_name') is-invalid @enderror" 
                                   id="buyer_name" name="buyer_name" value="{{ old('buyer_name') }}" required>
                            @error('buyer_name')
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

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="share_quantity_to_buy" class="form-label">Share Quantity to Buy <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('share_quantity_to_buy') is-invalid @enderror" 
                                   id="share_quantity_to_buy" name="share_quantity_to_buy" value="{{ old('share_quantity_to_buy') }}" min="1" required>
                            @error('share_quantity_to_buy')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="offered_price_per_share" class="form-label">Offered Price per Share <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('offered_price_per_share') is-invalid @enderror" 
                                   id="offered_price_per_share" name="offered_price_per_share" value="{{ old('offered_price_per_share') }}" 
                                   step="0.01" min="0" required>
                            @error('offered_price_per_share')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="citizenship_number" class="form-label">Citizenship Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('citizenship_number') is-invalid @enderror" 
                                   id="citizenship_number" name="citizenship_number" value="{{ old('citizenship_number') }}" required>
                            @error('citizenship_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="pan_number" class="form-label">PAN Number</label>
                            <input type="text" class="form-control @error('pan_number') is-invalid @enderror" 
                                   id="pan_number" name="pan_number" value="{{ old('pan_number') }}">
                            @error('pan_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="demat_account" class="form-label">Demat Account <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('demat_account') is-invalid @enderror" 
                                   id="demat_account" name="demat_account" value="{{ old('demat_account') }}" required>
                            @error('demat_account')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}">
                        @error('email')
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
                                    <label for="buy_application_doc" class="form-label">Application to Buy <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('buy_application_doc') is-invalid @enderror" 
                                           id="buy_application_doc" name="buy_application_doc" accept=".pdf,.jpg,.jpeg,.png" required>
                                    <small class="form-text text-muted">PDF, JPG, PNG (Max: 5MB)</small>
                                    @error('buy_application_doc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="buyer_citizenship" class="form-label">Citizenship <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('buyer_citizenship') is-invalid @enderror" 
                                           id="buyer_citizenship" name="buyer_citizenship" accept=".pdf,.jpg,.jpeg,.png" required>
                                    <small class="form-text text-muted">PDF, JPG, PNG (Max: 5MB)</small>
                                    @error('buyer_citizenship')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="buyer_cia_report" class="form-label">CIA Report <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('buyer_cia_report') is-invalid @enderror" 
                                           id="buyer_cia_report" name="buyer_cia_report" accept=".pdf,.jpg,.jpeg,.png" required>
                                    <small class="form-text text-muted">PDF, JPG, PNG (Max: 5MB)</small>
                                    @error('buyer_cia_report')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="buyer_tax_clearance" class="form-label">Tax Clearance <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('buyer_tax_clearance') is-invalid @enderror" 
                                           id="buyer_tax_clearance" name="buyer_tax_clearance" accept=".pdf,.jpg,.jpeg,.png" required>
                                    <small class="form-text text-muted">PDF, JPG, PNG (Max: 5MB)</small>
                                    @error('buyer_tax_clearance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="buyer_income_source" class="form-label">Income Source <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('buyer_income_source') is-invalid @enderror" 
                                           id="buyer_income_source" name="buyer_income_source" accept=".pdf,.jpg,.jpeg,.png" required>
                                    <small class="form-text text-muted">PDF, JPG, PNG (Max: 5MB)</small>
                                    @error('buyer_income_source')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="combine_application" class="form-label">Combine Application <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('combine_application') is-invalid @enderror" 
                                           id="combine_application" name="combine_application" accept=".pdf,.jpg,.jpeg,.png" required>
                                    <small class="form-text text-muted">PDF, JPG, PNG (Max: 5MB)</small>
                                    @error('combine_application')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="police_report" class="form-label">Police Report <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('police_report') is-invalid @enderror" 
                                           id="police_report" name="police_report" accept=".pdf,.jpg,.jpeg,.png" required>
                                    <small class="form-text text-muted">PDF, JPG, PNG (Max: 5MB)</small>
                                    @error('police_report')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="self_declaration" class="form-label">Self-Declaration Form <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('self_declaration') is-invalid @enderror" 
                                           id="self_declaration" name="self_declaration" accept=".pdf,.jpg,.jpeg,.png" required>
                                    <small class="form-text text-muted">PDF, JPG, PNG (Max: 5MB)</small>
                                    @error('self_declaration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Institutional Documents (Hidden by default) -->
                                <div class="col-md-6 mb-3" id="buyer-moa-aoa-upload" style="display: none;">
                                    <label for="buyer_moa_aoa" class="form-label">MOA & AOA <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('buyer_moa_aoa') is-invalid @enderror" 
                                           id="buyer_moa_aoa" name="buyer_moa_aoa" accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="form-text text-muted">PDF, JPG, PNG (Max: 5MB)</small>
                                    @error('buyer_moa_aoa')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3" id="buyer-decision-minute-upload" style="display: none;">
                                    <label for="buyer_decision_minute" class="form-label">Decision Minute <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('buyer_decision_minute') is-invalid @enderror" 
                                           id="buyer_decision_minute" name="buyer_decision_minute" accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="form-text text-muted">PDF, JPG, PNG (Max: 5MB)</small>
                                    @error('buyer_decision_minute')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="buyer_others" class="form-label">Others (Optional)</label>
                                    <input type="file" class="form-control @error('buyer_others') is-invalid @enderror" 
                                           id="buyer_others" name="buyer_others" accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="form-text text-muted">PDF, JPG, PNG (Max: 5MB)</small>
                                    @error('buyer_others')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('buy-applications.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Submit Buy Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="mb-0">Required Documents for Buy Application</h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Application to Buy</span>
                        <span class="badge bg-danger">Required</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Citizenship</span>
                        <span class="badge bg-danger">Required</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>CIA Report</span>
                        <span class="badge bg-danger">Required</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Tax Clearance</span>
                        <span class="badge bg-danger">Required</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Income Source</span>
                        <span class="badge bg-danger">Required</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center" id="buyer-moa-aoa" style="display: none;">
                        <span>MOA & AOA</span>
                        <span class="badge bg-warning">Institutional</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center" id="buyer-decision-minute" style="display: none;">
                        <span>Decision Minute</span>
                        <span class="badge bg-warning">Institutional</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Combine Application</span>
                        <span class="badge bg-info">Required</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Police Report</span>
                        <span class="badge bg-info">Required</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Self-Declaration Form</span>
                        <span class="badge bg-info">Required</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Others</span>
                        <span class="badge bg-secondary">Optional</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow mt-3">
            <div class="card-header">
                <h6 class="mb-0">Regulatory Notifications</h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item">
                        <i class="fas fa-building me-2 text-primary"></i>
                        <strong>SEBBON Notification</strong>
                        <br><small class="text-muted">Securities Board of Nepal notification required</small>
                    </div>
                    <div class="list-group-item">
                        <i class="fas fa-chart-line me-2 text-success"></i>
                        <strong>NEPSE Notification</strong>
                        <br><small class="text-muted">Nepal Stock Exchange notification required</small>
                    </div>
                    <div class="list-group-item">
                        <i class="fas fa-university me-2 text-info"></i>
                        <strong>NIA Notification</strong>
                        <br><small class="text-muted">National Insurance Authority notification</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Handle buyer category change
document.getElementById('buyer_category').addEventListener('change', function() {
    const buyerType = document.getElementById('buyer_type');
    
    if (this.value === 'existing_promoter') {
        // Auto-select institutional for existing promoter
        buyerType.value = 'institutional';
        buyerType.disabled = true;
        triggerBuyerTypeChange();
    } else if (this.value === 'public') {
        // Enable dropdown for public category
        buyerType.disabled = false;
        buyerType.value = '';
    } else {
        buyerType.disabled = false;
        buyerType.value = '';
    }
});

// Handle buyer type change
document.getElementById('buyer_type').addEventListener('change', triggerBuyerTypeChange);

function triggerBuyerTypeChange() {
    const buyerType = document.getElementById('buyer_type');
    const buyerMoaAoa = document.getElementById('buyer-moa-aoa');
    const buyerDecisionMinute = document.getElementById('buyer-decision-minute');
    const buyerMoaAoaUpload = document.getElementById('buyer-moa-aoa-upload');
    const buyerDecisionMinuteUpload = document.getElementById('buyer-decision-minute-upload');
    const moaAoaInput = document.getElementById('buyer_moa_aoa');
    const decisionMinuteInput = document.getElementById('buyer_decision_minute');
    
    if (buyerType.value === 'institutional') {
        buyerMoaAoa.style.display = 'block';
        buyerDecisionMinute.style.display = 'block';
        buyerMoaAoaUpload.style.display = 'block';
        buyerDecisionMinuteUpload.style.display = 'block';
        moaAoaInput.required = true;
        decisionMinuteInput.required = true;
    } else {
        buyerMoaAoa.style.display = 'none';
        buyerDecisionMinute.style.display = 'none';
        buyerMoaAoaUpload.style.display = 'none';
        buyerDecisionMinuteUpload.style.display = 'none';
        moaAoaInput.required = false;
        decisionMinuteInput.required = false;
    }
}

document.getElementById('share_quantity_to_buy').addEventListener('input', calculateTotal);
document.getElementById('offered_price_per_share').addEventListener('input', calculateTotal);

function calculateTotal() {
    const quantity = parseFloat(document.getElementById('share_quantity_to_buy').value) || 0;
    const price = parseFloat(document.getElementById('offered_price_per_share').value) || 0;
    const total = quantity * price;
    
    console.log('Total Amount: Rs. ' + total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
}

// Trigger change events if values are pre-selected
if (document.getElementById('buyer_category').value) {
    document.getElementById('buyer_category').dispatchEvent(new Event('change'));
}
if (document.getElementById('buyer_type').value) {
    triggerBuyerTypeChange();
}
</script>
@endsection
