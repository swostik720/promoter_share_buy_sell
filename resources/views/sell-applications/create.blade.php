@extends('layouts.app')

@section('title', 'New Sell Application - Promoter Share Management')
@section('page-title', 'New Sell Application')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">Sell Application Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('sell-applications.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="seller_id" class="form-label">Seller (Promoter) <span class="text-danger">*</span></label>
                        <select class="form-select @error('seller_id') is-invalid @enderror" id="seller_id" name="seller_id" required>
                            <option value="">Select Seller</option>
                            @foreach($shareholders as $shareholder)
                                <option value="{{ $shareholder->id }}" {{ old('seller_id') == $shareholder->id ? 'selected' : '' }}>
                                    {{ $shareholder->name }} ({{ number_format($shareholder->share_quantity) }} shares)
                                </option>
                            @endforeach
                        </select>
                        @error('seller_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="share_quantity_to_sell" class="form-label">Share Quantity to Sell <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('share_quantity_to_sell') is-invalid @enderror" 
                                   id="share_quantity_to_sell" name="share_quantity_to_sell" value="{{ old('share_quantity_to_sell') }}" min="1" required>
                            @error('share_quantity_to_sell')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="proposed_price_per_share" class="form-label">Proposed Price per Share</label>
                            <input type="number" class="form-control @error('proposed_price_per_share') is-invalid @enderror" 
                                   id="proposed_price_per_share" name="proposed_price_per_share" value="{{ old('proposed_price_per_share') }}" 
                                   step="0.01" min="0">
                            @error('proposed_price_per_share')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="application_date" class="form-label">Application Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('application_date') is-invalid @enderror" 
                               id="application_date" name="application_date" value="{{ old('application_date', date('Y-m-d')) }}" required>
                        @error('application_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason for Selling</label>
                        <textarea class="form-control @error('reason') is-invalid @enderror" 
                                  id="reason" name="reason" rows="3">{{ old('reason') }}</textarea>
                        @error('reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('sell-applications.index') }}" class="btn btn-secondary">
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
