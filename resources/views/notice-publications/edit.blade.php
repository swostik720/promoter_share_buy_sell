@extends('layouts.app')

@section('title', 'Edit Notice Publication - Promoter Share Management')
@section('page-title', 'Edit Notice Publication')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">Edit Notice Publication</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('notice-publications.update', $notice->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Sell Application</label>
                        <div class="border p-3 bg-light rounded">
                            <strong>{{ $notice->sellApplication->seller->name }}</strong> - 
                            {{ number_format($notice->sellApplication->share_quantity_to_sell) }} shares
                            <br><small class="text-muted">Applied: {{ $notice->sellApplication->application_date->format('M d, Y') }}</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="publication_date" class="form-label">Publication Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('publication_date') is-invalid @enderror" 
                                   id="publication_date" name="publication_date" value="{{ old('publication_date', $notice->publication_date->format('Y-m-d')) }}" required>
                            @error('publication_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="newspaper_name" class="form-label">Newspaper Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('newspaper_name') is-invalid @enderror" 
                                   id="newspaper_name" name="newspaper_name" value="{{ old('newspaper_name', $notice->newspaper_name) }}" 
                                   placeholder="e.g., The Himalayan Times" required>
                            @error('newspaper_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notice_reference" class="form-label">Notice Reference</label>
                        <input type="text" class="form-control @error('notice_reference') is-invalid @enderror" 
                               id="notice_reference" name="notice_reference" value="{{ old('notice_reference', $notice->notice_reference) }}" 
                               placeholder="e.g., NP-2024-001">
                        @error('notice_reference')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="notice_content" class="form-label">Notice Content <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('notice_content') is-invalid @enderror" 
                                  id="notice_content" name="notice_content" rows="6" required>{{ old('notice_content', $notice->notice_content) }}</textarea>
                        <small class="form-text text-muted">Enter the complete notice content as it will appear in the newspaper</small>
                        @error('notice_content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('notice-publications.show', $notice->id) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Notice
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
