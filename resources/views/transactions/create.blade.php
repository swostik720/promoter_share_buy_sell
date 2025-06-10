@extends('layouts.app')

@section('title', 'Transaction with Regulatory Notifications - Promoter Share Management')
@section('page-title', 'Create Transaction with Regulatory Notifications')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">Transaction Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('transactions.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="buy_application_id" class="form-label">Approved Buy Application <span class="text-danger">*</span></label>
                        <select class="form-select @error('buy_application_id') is-invalid @enderror" id="buy_application_id" name="buy_application_id" required>
                            <option value="">Select Buy Application</option>
                            @foreach($buyApplications as $buyApp)
                                <option value="{{ $buyApp->id }}" 
                                        data-seller="{{ $buyApp->sellApplication->seller->name }}"
                                        data-buyer="{{ $buyApp->buyer_name }}"
                                        data-quantity="{{ $buyApp->share_quantity_to_buy }}"
                                        data-price="{{ $buyApp->offered_price_per_share }}"
                                        {{ old('buy_application_id', request('buy_application_id')) == $buyApp->id ? 'selected' : '' }}>
                                    {{ $buyApp->buyer_name }} → {{ $buyApp->sellApplication->seller->name }} 
                                    ({{ number_format($buyApp->share_quantity_to_buy) }} shares @ Rs. {{ number_format($buyApp->offered_price_per_share, 2) }})
                                </option>
                            @endforeach
                        </select>
                        @error('buy_application_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="transaction-details" style="display: none;">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Seller Details</h6>
                                        <p class="card-text" id="seller-name">-</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Buyer Details</h6>
                                        <p class="card-text" id="buyer-name">-</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="share_quantity" class="form-label">Share Quantity <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('share_quantity') is-invalid @enderror" 
                                   id="share_quantity" name="share_quantity" value="{{ old('share_quantity') }}" min="1" required>
                            @error('share_quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="price_per_share" class="form-label">Price per Share <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('price_per_share') is-invalid @enderror" 
                                   id="price_per_share" name="price_per_share" value="{{ old('price_per_share') }}" 
                                   step="0.01" min="0" required>
                            @error('price_per_share')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="total_amount" class="form-label">Total Amount</label>
                            <input type="text" class="form-control" id="total_amount" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="transaction_date" class="form-label">Transaction Date (Date Selection) <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('transaction_date') is-invalid @enderror" 
                               id="transaction_date" name="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}" required>
                        @error('transaction_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Regulatory Notification Dates -->
                    <div class="card bg-light mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">Regulatory Notification Dates</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="sebbon_notification_date" class="form-label">SEBBON Notification Date</label>
                                    <input type="date" class="form-control" id="sebbon_notification_date" name="sebbon_notification_date" value="{{ old('sebbon_notification_date') }}">
                                    <small class="text-muted">Inform to SEBBON to sell</small>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="nepse_notification_date" class="form-label">NEPSE Notification Date</label>
                                    <input type="date" class="form-control" id="nepse_notification_date" name="nepse_notification_date" value="{{ old('nepse_notification_date') }}">
                                    <small class="text-muted">Inform to NEPSE to sell</small>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="nia_notification_date" class="form-label">NIA Notification Date</label>
                                    <input type="date" class="form-control" id="nia_notification_date" name="nia_notification_date" value="{{ old('nia_notification_date') }}">
                                    <small class="text-muted">Inform to NIA</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Create Transaction
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="mb-0">Regulatory Notifications Required</h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-building me-2 text-primary"></i>
                                <strong>SEBBON</strong>
                            </div>
                            <span class="badge bg-danger">Required</span>
                        </div>
                        <small class="text-muted">Securities Board of Nepal notification for share transfer</small>
                    </div>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-chart-line me-2 text-success"></i>
                                <strong>NEPSE</strong>
                            </div>
                            <span class="badge bg-danger">Required</span>
                        </div>
                        <small class="text-muted">Nepal Stock Exchange notification for trading</small>
                    </div>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-university me-2 text-info"></i>
                                <strong>NIA</strong>
                            </div>
                            <span class="badge bg-danger">Required</span>
                        </div>
                        <small class="text-muted">National Insurance Authority notification</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow mt-3">
            <div class="card-header">
                <h6 class="mb-0">Required Documents for Upload</h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item">
                        <i class="fas fa-file-pdf me-2 text-danger"></i>
                        SEBBON Notification Document
                    </div>
                    <div class="list-group-item">
                        <i class="fas fa-file-pdf me-2 text-success"></i>
                        NEPSE Notification Document
                    </div>
                    <div class="list-group-item">
                        <i class="fas fa-file-pdf me-2 text-info"></i>
                        NIA Notification Document
                    </div>
                </div>
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Documents can be uploaded after creating the transaction.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('buy_application_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const detailsDiv = document.getElementById('transaction-details');
    
    if (selectedOption.value) {
        // Show transaction details
        detailsDiv.style.display = 'block';
        
        // Populate details
        document.getElementById('seller-name').textContent = selectedOption.dataset.seller;
        document.getElementById('buyer-name').textContent = selectedOption.dataset.buyer;
        
        // Auto-fill form fields
        document.getElementById('share_quantity').value = selectedOption.dataset.quantity;
        document.getElementById('price_per_share').value = selectedOption.dataset.price;
        
        calculateTotal();
    } else {
        detailsDiv.style.display = 'none';
    }
});

document.getElementById('share_quantity').addEventListener('input', calculateTotal);
document.getElementById('price_per_share').addEventListener('input', calculateTotal);

function calculateTotal() {
    const quantity = parseFloat(document.getElementById('share_quantity').value) || 0;
    const price = parseFloat(document.getElementById('price_per_share').value) || 0;
    const total = quantity * price;
    
    document.getElementById('total_amount').value = 'Rs. ' + total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
}
</script>
@endsection
