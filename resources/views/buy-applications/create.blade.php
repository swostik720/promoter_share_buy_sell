@extends('layouts.app')

@section('title', 'New Buy Application - Promoter Share Management')
@section('page-title', 'New Buy Application')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">Buy Application Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('buy-applications.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="sell_application_id" class="form-label">Available Sell Applications <span class="text-danger">*</span></label>
                        <select class="form-select @error('sell_application_id') is-invalid @enderror" id="sell_application_id" name="sell_application_id" required>
                            <option value="">Select Sell Application</option>
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
                            <label for="citizenship_number" class="form-label">Citizenship Number</label>
                            <input type="text" class="form-control @error('citizenship_number') is-invalid @enderror" 
                                   id="citizenship_number" name="citizenship_number" value="{{ old('citizenship_number') }}">
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
                            <label for="demat_account" class="form-label">Demat Account</label>
                            <input type="text" class="form-control @error('demat_account') is-invalid @enderror" 
                                   id="demat_account" name="demat_account" value="{{ old('demat_account') }}">
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

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('buy-applications.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Submit Application
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
document.getElementById('share_quantity_to_buy').addEventListener('input', calculateTotal);
document.getElementById('offered_price_per_share').addEventListener('input', calculateTotal);

function calculateTotal() {
    const quantity = parseFloat(document.getElementById('share_quantity_to_buy').value) || 0;
    const price = parseFloat(document.getElementById('offered_price_per_share').value) || 0;
    const total = quantity * price;
    
    // You can add a total display element if needed
    console.log('Total Amount: Rs. ' + total.toFixed(2));
}
</script>
@endsection
