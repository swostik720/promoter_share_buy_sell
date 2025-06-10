@extends('layouts.app')

@section('title', 'Upload Document - Promoter Share Management')
@section('page-title', 'Upload Document')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">Document Upload</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="documentable_type" class="form-label">Related To <span class="text-danger">*</span></label>
                            <select class="form-select @error('documentable_type') is-invalid @enderror" id="documentable_type" name="documentable_type" required>
                                <option value="">Select Type</option>
                                <option value="App\Models\Shareholder" {{ old('documentable_type') == 'App\Models\Shareholder' ? 'selected' : '' }}>Shareholder</option>
                                <option value="App\Models\SellApplication" {{ old('documentable_type') == 'App\Models\SellApplication' ? 'selected' : '' }}>Sell Application</option>
                                <option value="App\Models\BuyApplication" {{ old('documentable_type') == 'App\Models\BuyApplication' ? 'selected' : '' }}>Buy Application</option>
                            </select>
                            @error('documentable_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="documentable_id" class="form-label">Select Item <span class="text-danger">*</span></label>
                            <select class="form-select @error('documentable_id') is-invalid @enderror" id="documentable_id" name="documentable_id" required>
                                <option value="">Select Item</option>
                            </select>
                            @error('documentable_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="document_type" class="form-label">Document Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('document_type') is-invalid @enderror" id="document_type" name="document_type" required>
                            <option value="">Select Document Type</option>
                            <option value="application" {{ old('document_type') == 'application' ? 'selected' : '' }}>Application</option>
                            <option value="citizenship" {{ old('document_type') == 'citizenship' ? 'selected' : '' }}>Citizenship</option>
                            <option value="tax_clearance" {{ old('document_type') == 'tax_clearance' ? 'selected' : '' }}>Tax Clearance</option>
                            <option value="cia_report" {{ old('document_type') == 'cia_report' ? 'selected' : '' }}>CIA Report</option>
                            <option value="moa_aoa" {{ old('document_type') == 'moa_aoa' ? 'selected' : '' }}>MOA & AOA</option>
                            <option value="decision_minute" {{ old('document_type') == 'decision_minute' ? 'selected' : '' }}>Decision Minute</option>
                            <option value="income_source" {{ old('document_type') == 'income_source' ? 'selected' : '' }}>Income Source</option>
                            <option value="combine_application" {{ old('document_type') == 'combine_application' ? 'selected' : '' }}>Combine Application</option>
                            <option value="police_report" {{ old('document_type') == 'police_report' ? 'selected' : '' }}>Police Report</option>
                            <option value="self_declaration" {{ old('document_type') == 'self_declaration' ? 'selected' : '' }}>Self Declaration</option>
                            <option value="sebbon_notification" {{ old('document_type') == 'sebbon_notification' ? 'selected' : '' }}>SEBBON Notification</option>
                            <option value="nepse_notification" {{ old('document_type') == 'nepse_notification' ? 'selected' : '' }}>NEPSE Notification</option>
                            <option value="other" {{ old('document_type') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('document_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="file" class="form-label">File <span class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" required>
                        <small class="form-text text-muted">Maximum file size: 10MB. Supported formats: PDF, DOC, DOCX, JPG, PNG</small>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea class="form-control @error('remarks') is-invalid @enderror" id="remarks" name="remarks" rows="3">{{ old('remarks') }}</textarea>
                        @error('remarks')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('documents.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload me-2"></i>Upload Document
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('documentable_type').addEventListener('change', function() {
    const type = this.value;
    const itemSelect = document.getElementById('documentable_id');
    
    // Clear existing options
    itemSelect.innerHTML = '<option value="">Select Item</option>';
    
    if (type) {
        // You would typically make an AJAX call here to fetch the items
        // For now, we'll just enable the select
        itemSelect.disabled = false;
        
        // Example static data - replace with AJAX call
        if (type === 'App\\Models\\Shareholder') {
            // Add shareholder options
            itemSelect.innerHTML += '<option value="1">John Doe</option>';
            itemSelect.innerHTML += '<option value="2">Jane Smith</option>';
        } else if (type === 'App\\Models\\SellApplication') {
            // Add sell application options
            itemSelect.innerHTML += '<option value="1">Sell Application #1</option>';
        } else if (type === 'App\\Models\\BuyApplication') {
            // Add buy application options
            itemSelect.innerHTML += '<option value="1">Buy Application #1</option>';
        }
    } else {
        itemSelect.disabled = true;
    }
});
</script>
@endsection
