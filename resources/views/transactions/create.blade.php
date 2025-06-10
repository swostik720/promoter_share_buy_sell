@extends('layouts.app')

@section('title', 'New Transaction - Promoter Share Management')
@section('page-title', 'New Transaction')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
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
                        <label for="transaction_date" class="form-label">Transaction Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('transaction_date') is-invalid @enderror" 
                               id="transaction_date" name="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}" required>
                        @error('transaction_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
