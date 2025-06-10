@extends('layouts.app')

@section('title', 'Add Shareholder - Promoter Share Management')
@section('page-title', 'Add New Shareholder')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">Shareholder Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('shareholders.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="">Select Type</option>
                                <option value="individual" {{ old('type') == 'individual' ? 'selected' : '' }}>Individual</option>
                                <option value="institutional" {{ old('type') == 'institutional' ? 'selected' : '' }}>Institutional</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                <option value="">Select Category</option>
                                <option value="promoter" {{ old('category') == 'promoter' ? 'selected' : '' }}>Promoter</option>
                                <option value="public" {{ old('category') == 'public' ? 'selected' : '' }}>Public</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="share_quantity" class="form-label">Share Quantity <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('share_quantity') is-invalid @enderror" 
                                   id="share_quantity" name="share_quantity" value="{{ old('share_quantity') }}" min="1" required>
                            @error('share_quantity')
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
                        <a href="{{ route('shareholders.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save Shareholder
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
